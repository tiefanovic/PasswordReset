<?php
/**
 * InstallSchema
 *
 * @copyright Copyright © 2020 AWstreams. All rights reserved.
 * @author    ahmed.atef@awstreams.com
 */

namespace Mageserv\PasswordReset\Setup;


use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    const TABLE_NAME = "password_reset_codes";

    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $tableName = $setup->getTable(self::TABLE_NAME);
        if(!$setup->getConnection()->isTableExists($tableName)){
            $table = $setup->getConnection()->newTable($tableName);
            $table->addColumn(
                'code_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['primary'=>true, 'identity'=>true, 'unsigned'=>true, 'nullable'=>false],
                'Reset Code ID'
            )->addColumn(
                'customer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['primary'=>true, 'identity'=>false, 'unsigned'=>true, 'nullable'=>false],
                'Customer ID'
            )->addColumn(
                'code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Password reset code'
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],                'Created At'
            )->addForeignKey(
                $setup->getFkName($tableName, 'customer_id', $setup->getTable('customer_entity'), 'entity_id'),
                'customer_id',
                $setup->getTable('customer_entity'),
                'entity_id',
                'CASCADE'
            )->setComment("Password resets codes");
            $setup->getConnection()->createTable($table);
        }
        $setup->endSetup();
    }
}