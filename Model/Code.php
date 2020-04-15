<?php
/**
 * Code
 *
 * @copyright Copyright © 2020 AWstreams. All rights reserved.
 * @author    ahmed.atef@awstreams.com
 */

namespace Mageserv\PasswordReset\Model;


use Magento\Framework\Model\AbstractModel;

class Code extends AbstractModel
{
    public function _construct()
    {
        $this->_init(\Mageserv\PasswordReset\Model\ResourceModel\Code::class);
    }
}