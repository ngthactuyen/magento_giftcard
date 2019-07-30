<?php
namespace Mageplaza\GiftCard\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        // TODO: Implement upgrade() method.
        $installer = $setup;
        $installer->startSetup();
        if (version_compare($context->getVersion(), '2.0.0', '<')){
//              Create table giftcard_history
            if (!$installer->tableExists('giftcard_history')){
                $table = $installer->getConnection()->newTable($installer->getTable('giftcard_history'))
                    ->addColumn(
                        'history_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'primary' => true,
                            'identity' =>true,
                            'nullable' => false
                        ],
                        'History Id'
                    )
                    ->addColumn(
                        'giftcard_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [],
                        'Gift Card Id'
                    )
                    ->addColumn(
                        'customer_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        '10',
                        [
                            'unsigned' => true
                        ],
                        'Customer Id'
                    )
                    ->addColumn(
                        'amount',
                        \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                        '12,4',
                        [],
                        'Amount is changed'
                    )
                    ->addColumn(
                        'action',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        [],
                        'Action change'
                    )
                    ->addColumn(
                        'action_time',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        [
                            'nullable' => false,
                            'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE
                        ],
                        'Action time'
                    );
                $installer->getConnection()->createTable($table);
            }

//            add foreignkey
            if ($installer->tableExists('giftcard_history')){
                $connect = $installer->getConnection();
                if ($installer->tableExists('giftcard_code')){
                    $connect->addForeignKey(
                        $installer->getFkName('giftcard_history', 'giftcard_id', 'giftcard_code', 'giftcard_id'),
                        $installer->getTable('giftcard_history'),
                        'giftcard_id',
                        $installer->getTable('giftcard_code'),
                        'giftcard_id',
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    );
                }
                if ($installer->tableExists('customer_entity')){
                    $connect->addForeignKey(
                        $installer->getFkName('giftcard_history', 'customer_id', 'customer_entity', 'entity_id'),
                        $installer->getTable('giftcard_history'),
                        'customer_id',
                        $installer->getTable('customer_entity'),
                        'entity_id',
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    );
                }

            }

//            adding column to table customer_entity
            if ($installer->tableExists('customer_entity')){
                $installer->getConnection()->addColumn(
                    $installer->getTable('customer_entity'),
                    'giftcard_balance',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                        'nullable' => true,
                        'length' => '12,4',
                        'comment' => 'Gift Card Balance'
                    ]
                );
            }
        }
        $installer->endSetup();
    }

}