<?php
/**
 * PasswordResetRepositoryInterface
 *
 * @copyright Copyright © 2020 AWstreams. All rights reserved.
 * @author    ahmed.atef@awstreams.com
 */

namespace Mageserv\PasswordReset\Api;


interface PasswordResetRepositoryInterface
{
    /**
     * @param string $email
     * @return \Mageserv\PasswordReset\Api\Data\ResultInterface
     */
    public function resetPassword($email);

    /**
     * @param string $email
     * @param string $code
     * @return \Mageserv\PasswordReset\Api\Data\ResultInterface
     */
    public function validateResetCode($email, $code);

    /**
     * @param string $email
     * @param string $code
     * @param string $password
     * @return \Mageserv\PasswordReset\Api\Data\ResultInterface
     */
    public function changePassword($email, $code, $password);
}