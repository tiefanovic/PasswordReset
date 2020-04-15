<?php
/**
 * Data
 *
 * @copyright Copyright © 2020 AWstreams. All rights reserved.
 * @author    ahmed.atef@awstreams.com
 */

namespace Mageserv\PasswordReset\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const PSWD_ENABLE_XML_PATH = "password_codes/general/enable";
    const PSWD_CODE_LENGTH_XML_PATH = "password_codes/general/code_length";
    const PSWD_EMAIL_TEMPLATE_XML_PATH = "password_codes/general/email_template";
    const PSWD_EMAIL_SUBJECT_XML_PATH = "password_codes/general/subject";
    const GENERAL_EMAIL_SENDER_NAME_XML_PATH = "trans_email/ident_general/name";
    const GENERAL_EMAIL_SENDER_EMAIL_XML_PATH = "trans_email/ident_general/email";

    protected $_storeManager;
    protected $_mathRandom;
    protected $_transportBuilder;
    protected $_inlineTranslation;
    protected $_escaper;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface,
        \Magento\Framework\Math\Random $mathRandom,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Escaper $escaper
    )
    {
        $this->_storeManager = $storeManagerInterface;
        $this->_mathRandom = $mathRandom;
        $this->_transportBuilder = $transportBuilder;
        $this->_inlineTranslation = $inlineTranslation;
        $this->_escaper = $escaper;
        parent::__construct($context);
    }

    protected function _getConfig($value)
    {
        return $this->scopeConfig->getValue(
            $value,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->_storeManager->getStore()->getCode()
        );
    }

    public function isModuleEnabled()
    {
        return $this->_getConfig(self::PSWD_ENABLE_XML_PATH);
    }

    public function getResetCodeLength()
    {
        return $this->_getConfig(self::PSWD_CODE_LENGTH_XML_PATH);
    }

    public function getResetPasswordEmailTemplate()
    {
        return $this->_getConfig(self::PSWD_EMAIL_TEMPLATE_XML_PATH);
    }

    public function generateResetCode($length = 4)
    {
        if ($this->getResetCodeLength() > 0) {
            $length = $this->getResetCodeLength();
        }
        $code = "";
        for ($i = 0; $i < $length; $i++) {
            $code .= $this->_mathRandom->getRandomNumber(0, 9);
        }
        return $code;
    }

    protected function getEmailSenderName()
    {
        return $this->_getConfig(self::GENERAL_EMAIL_SENDER_NAME_XML_PATH);
    }

    protected function getEmailSenderEmail()
    {
        return $this->_getConfig(self::GENERAL_EMAIL_SENDER_EMAIL_XML_PATH);
    }

    /**
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param string $code
     * @return bool
     * @throws \Exception
     */
    public function sendResetEmail($customer, $code)
    {
        $this->_inlineTranslation->suspend();

        $sender = [
            'name' => $this->_escaper->escapeHtml($this->getEmailSenderName()),
            'email' => $this->_escaper->escapeHtml($this->getEmailSenderEmail()),
        ];
        $transport = $this->_transportBuilder
            ->setTemplateIdentifier($this->getResetPasswordEmailTemplate())
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ]
            )
            ->setTemplateVars([
                'email_subject' => $this->getResetEmailSubject(),
                'customer_name' => $customer->getFirstname(),
                'code' => $code
            ])
            ->setFrom($sender)
            ->addTo($customer->getEmail())
            ->getTransport();

        $transport->sendMessage();
        $this->_inlineTranslation->resume();
        return true;
    }

    protected function getResetEmailSubject()
    {
        return $this->_getConfig(self::PSWD_EMAIL_SUBJECT_XML_PATH);
    }
}