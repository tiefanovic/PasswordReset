<?php
/**
 * Code
 *
 * @copyright Copyright © 2020 AWstreams. All rights reserved.
 * @author    ahmed.atef@awstreams.com
 */

namespace Mageserv\PasswordReset\Model\ResourceModel;


use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Mageserv\PasswordReset\Setup\InstallSchema;

class Code extends AbstractDb
{
    protected $_mainTable = InstallSchema::TABLE_NAME;
    protected $_idFieldName = "code_id";

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init($this->_mainTable, $this->_idFieldName);
    }
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        parent::_beforeSave($object);
        $this->getConnection()->delete($this->getTable($this->_mainTable), ['customer_id = ?' => $object->getData('customer_id')]);
        return $this;
    }
    public function cleanOldData($customerId){
        $this->getConnection()->delete($this->getTable($this->_mainTable), ['customer_id = ?' => $customerId]);
        return $this;
    }
}