<?php
declare(strict_types=1);
 
namespace Bashconsole\Shopfinder\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.Generic.CodeAnalysis.UnusedFunctionParameter)
     */
     
    // @codingStandardsIgnoreStart
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    
    // @codingStandardsIgnoreEnd
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('bashconsole_shopfinder_stores')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('bashconsole_shopfinder_stores'));
            $table->addColumn(
                    'entity_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'Store ID'
                )
                ->addColumn(
                    'store_id',
                    Table::TYPE_SMALLINT,
                    null,
                    [
                        'unsigned'  => true,
                        'nullable'  => false
                    ],
                    'Store ID'
                )
                ->addColumn(
                    'shop_id',
                    Table::TYPE_TEXT,
                    255,
                    [
                        'nullable'  => false,
                        'unique' => true
                    ],
                    'Unique shop identifier'
                )
                ->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable'  => false,],
                    'Store Name'
                )
                ->addColumn(
                    'country',
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'Country'
                )
                ->addColumn(
                    'image',
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'Image'
                )
                ->addColumn(
                    'status',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'nullable'  => false,
                        'default'   => '1',
                    ],
                    'Status'
                )
                ->addColumn(
                    'updated_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    [],
                    'Update at'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    [],
                    'Creation Time'
                )
                ->setComment('Table of Shopfinder shops');
                
            $installer->getConnection()->createTable($table);
            
            $installer->getConnection()->addIndex(
                $installer->getTable('bashconsole_shopfinder_stores'),
                $setup->getIdxName(
                    $installer->getTable('bashconsole_shopfinder_stores'),
                    ['shop_id'],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                [
                    'shop_id'
                ],
                AdapterInterface::INDEX_TYPE_UNIQUE
            );

            $installer->getConnection()->addIndex(
                $installer->getTable('bashconsole_shopfinder_stores'),
                $setup->getIdxName(
                    $installer->getTable('bashconsole_shopfinder_stores'),
                    ['name','photo'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                [
                    'name',
                    'country'
                ],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            );
            
            $installer->endSetup();
            
        }
    }
}