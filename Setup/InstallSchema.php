<?php
/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

declare(strict_types=1);

namespace Yireo\SalesBlock2\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Zend_Db_Exception;

/**
 * Class InstallSchema
 *
 * @package Yireo\SalesBlock2\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $installer
     * @param ModuleContextInterface $context
     *
     * @throws Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $installer, ModuleContextInterface $context)
    {
        $installer->startSetup();
        $connection = $installer->getConnection();

        $table = $connection->newTable(
            $installer->getTable('salesblock_rule')
        )->addColumn(
            'rule_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Rule ID'
        )->addColumn(
            'label',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Label'
        )->addColumn(
            'email_value',
            Table::TYPE_TEXT,
            null,
            ['nullable' => true],
            'Email value'
        )->addColumn(
            'ip_value',
            Table::TYPE_TEXT,
            null,
            ['nullable' => true],
            'IP value'
        )->addColumn(
            'frontend_label',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Frontend label'
        )->addColumn(
            'frontend_text',
            Table::TYPE_TEXT,
            null,
            ['nullable' => true],
            'Frontend text'
        )->addColumn(
            'status',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Status'
        )->addColumn(
            'created',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            'User Created Time'
        )->addColumn(
            'modified',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
            'User Modified Time'
        )->setComment(
            'Yireo_SalesBlock2 Rules Table'
        );

        $connection->createTable($table);
        $installer->endSetup();
    }
}