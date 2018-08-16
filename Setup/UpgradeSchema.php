<?php
/**
 * Copyright © Wizzaro. All rights reserved.
 */
namespace Wizzaro\MagentoFileWithAttachmentCustomOptionType\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
 
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @throws \Zend_Db_Exception
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $setup->getConnection()->addColumn($setup->getTable('catalog_product_option'), 'attachment_info', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'nullable' => true,
            'default' => null,
            'comment' => 'Attachment Info',
        ]);

        $setup->getConnection()->addColumn($setup->getTable('catalog_product_option'), 'attachment_label', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'nullable' => true,
            'default' => null,
            'comment' => 'Attachment Label',
        ]);
 
        $setup->getConnection()->addColumn($setup->getTable('catalog_product_option'), 'attachment_path', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'nullable' => true,
            'default' => null,
            'comment' => 'Attachment Path',
        ]);

        $setup->endSetup();
    }
}