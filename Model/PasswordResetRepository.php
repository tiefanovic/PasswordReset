<?php
/**
 * PasswordResetRepository
 *
 * @copyright Copyright © 2020 AWstreams. All rights reserved.
 * @author    ahmed.atef@awstreams.com
 */

namespace Mageserv\PasswordReset\Model;


class PasswordResetRepository implements \Mageserv\PasswordReset\Api\PasswordResetRepositoryInterface
{
    protected $_customerRepository;
    protected $_resultFactory;
    protected $_codeFactory;
    protected $_helper;
    protected $_encryptor;
    protected $_codeResource;
    public function __construct(
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Mageserv\PasswordReset\Api\Data\ResultInterfaceFactory $resultFactory,
        \Mageserv\PasswordReset\Model\CodeFactory $codeFactory,
        \Mageserv\PasswordReset\Model\ResourceModel\Code $codeResource,
        \Mageserv\PasswordReset\Helper\Data $helper,
        \Magento\Framework\Encryption\Encryptor $encryptor
    )
    {
        $this->_customerRepository = $customerRepositoryInterface;
        $this->_resultFactory = $resultFactory;
        $this->_codeFactory = $codeFactory;
        $this->_helper = $helper;
        $this->_encryptor = $encryptor;
        $this->_codeResource = $codeResource;
    }

    /**
     * @param string $email
     * @return \Mageserv\PasswordReset\Api\Data\ResultInterface
     */
    public function resetPassword($email)
    {
        /** @var \Mageserv\PasswordReset\Api\Data\ResultInterface $result */
        $result = $this->_resultFactory->create();
        try{
            $customer = $this->_customerRepository->get($email);
            // create code and send Email
            $code = $this->_codeFactory->create();
            $code->setData([
               'customer_id' => $customer->getId(),
                'code' => $this->_helper->generateResetCode()
            ])->save();
            $this->_helper->sendResetEmail($customer, $code->getCode());
            $result->setSuccess(true)->setMessage(__("Password reset requested, Please check your email"));
        }catch (\Magento\Framework\Exception\NoSuchEntityException $exception){
            $result->setSuccess(false)->setMessage(__("Email %1 couldn't be found", $email));
        }catch(\Exception $exception){
            $result->setSuccess(false)->setMessage($exception->getMessage());
        }
        return $result;
    }

    /**
     * @param string $email
     * @param string $code
     * @return \Mageserv\PasswordReset\Api\Data\ResultInterface
     */
    public function validateResetCode($email, $code)
    {
        $result = $this->_resultFactory->create();
        try{
            $customer = $this->_customerRepository->get($email);
            // create code and send Email
            $codeCollection = $this->_codeFactory->create()->getCollection()->addFieldToFilter('customer_id', $customer->getId());
            if($codeCollection->getSize()){
                $codeItem = $codeCollection->getFirstItem();
                if($codeItem->getCode() == $code){
                    $result->setSuccess(true)->setMessage(__("Code Valid, Please type your new password"));
                }else{
                    throw new \Exception(__('Reset code doesn\'t match'));
                }
            }else{
                throw new \Exception(__('Code not found, Please reset password first'));
            }
        }catch (\Magento\Framework\Exception\NoSuchEntityException $exception){
            $result->setSuccess(false)->setMessage(__("Email %1 couldn't be found", $email));
        }catch(\Exception $exception){
            $result->setSuccess(false)->setMessage($exception->getMessage());
        }
        return $result;
    }

    /**
     * @param string $email
     * @param string $code
     * @param string $password
     * @return \Mageserv\PasswordReset\Api\Data\ResultInterface
     */
    public function changePassword($email, $code, $password)
    {
        $result = $this->validateResetCode($email, $code);
        if($result->getSuccess()){
            try{
                /** @TODO change Password */
                $customer = $this->_customerRepository->get($email);
                $this->_customerRepository->save($customer, $this->_encryptor->getHash($password, true));
                $this->_codeResource->cleanOldData($customer->getId());
                $result->setSuccess(true)->setMessage(__("Password Changed Successfully"));
            }catch(\Exception $exception){
                $result->setSuccess(false)->setMessage($exception->getMessage());
            }
        }
        return $result;
    }
}