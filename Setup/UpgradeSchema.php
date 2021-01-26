<?php
namespace Magepow\Sitemapexclusion\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * Class InstallSchema
 * @package Magepow\Sitemapexclusion\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $connection = $installer->getConnection();

        if (version_compare($context->getVersion(), '1.1.1', '<')) {
            if ($installer->tableExists('cms_page')) {
                $connection->modifyColumn(
                    $installer->getTable('cms_page'),
                    'xml_sitemap_exclude',
                    ['type' => Table::TYPE_INTEGER, 'nullable' => true]
                );
            }
        }

        $installer->endSetup();
    }
}
