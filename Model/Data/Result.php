<?php
/**
 * Result
 *
 * @copyright Copyright © 2020 AWstreams. All rights reserved.
 * @author    ahmed.atef@awstreams.com
 */

namespace Mageserv\PasswordReset\Model\Data;

class Result extends \Magento\Framework\DataObject implements \Mageserv\PasswordReset\Api\Data\ResultInterface
{

    /**
     * @return bool
     */
    public function getSuccess()
    {
        return $this->getData(self::SUCCESS);
    }

    /**
     * @return string[]|string
     */
    public function getMessage()
    {
         return $this->getData(self::MESSAGE);
    }

    /**
     * @param bool $success
     * @return $this
     */
    public function setSuccess($success)
    {
        $this->setData(self::SUCCESS, $success);
        return $this;
    }

    /**
     * @param string[]|string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->setData(self::MESSAGE, $message);
        return $this;
    }

}