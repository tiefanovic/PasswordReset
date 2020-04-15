<?php
/**
 * Created by PhpStorm.
 * User: Tiefanovic
 * Date: 3/5/2020
 * Time: 9:37 AM
 */

namespace Mageserv\PasswordReset\Api\Data;


interface ResultInterface
{
    const SUCCESS = 'success';
    const MESSAGE = 'message';
    /*const RESPONSE = 'data';*/

    /**
     * @return bool
     */
    public function getSuccess();

    /**
     * @return string[]|string
     */
    public function getMessage();


    /**
     * @param bool $success
     * @return $this
     */
    public function setSuccess($success);

    /**
     * @param string[]|string $message
     * @return $this
     */
    public function setMessage($message);


}