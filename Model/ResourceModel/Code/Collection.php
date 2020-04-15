<?php
/**
 * Collection
 *
 * @copyright Copyright © 2020 AWstreams. All rights reserved.
 * @author    ahmed.atef@awstreams.com
 */

namespace Mageserv\PasswordReset\Model\ResourceModel\Code;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(\Mageserv\PasswordReset\Model\Code::class,\Mageserv\PasswordReset\Model\ResourceModel\Code::class );
    }
}