<?php
/**
 * Created by PhpStorm.
 * User: Tuyen
 * Date: 13-Jun-19
 * Time: 05:24 PM
 */
namespace Mageplaza\GiftCard\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('giftcard_code'))
        {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('giftcard_code')
            )
                ->addColumn(
                    'giftcard_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'primary' => true,
                        'identity' => true,
                        'nullable' => false
                    ],
                    'GiftCard ID'
                )
                ->addColumn(
                    'code',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    [],
                    'GiftCard Code'
                )
                ->addColumn(
                    'balance',
                    \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    '12,4',
                    [],
                    'GiftCard Balance'
                )
                ->addColumn(
                    'amount_used',
                    \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    '12,4',
                    [],
                    'GiftCard Amount Used'
                )
                ->addColumn(
                    'create_from',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    [],
                    'GiftCard Create From'
                )
                ->addColumn(
                    'create_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    [
                        'nullable' => false,
                        'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT
                    ],
                    'GiftCard Create At'
                )
                ->setComment('GiftCard Table');
            $installer->getConnection()->createTable($table);
            $installer->getConnection()->addIndex(
                $installer->getTable('giftcard_code'),
                $setup->getIdxName(
                    $installer->getTable('giftcard_code'),
                    ['code', 'balance', 'amount_used', 'create_from', 'create_at'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['code', 'balance', 'amount_used', 'create_from', 'create_at'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
            );
        }
        $installer->endSetup();
    }
}