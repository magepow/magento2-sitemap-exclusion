<?php

namespace Magepow\Sitemapexclusion\Setup;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Class InstallData
 * @package Magepow\Sitemapexclusion\Setup
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var CategorySetupFactory
     */
    protected $categorySetupFactory;

    /**
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * InstallData constructor.
     *
     * @param EavSetupFactory $eavSetupFactory
     * @param CategorySetupFactory $categorySetupFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        CategorySetupFactory $categorySetupFactory
    ) {
        $this->categorySetupFactory = $categorySetupFactory;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        /**
         * Product attribute
         */
        $eavSetup->removeAttribute(Product::ENTITY, 'xml_sitemap_exclude');
        $eavSetup->addAttribute(Product::ENTITY, 'xml_sitemap_exclude', [
            'type'                    => 'int',
            'backend'                 => '',
            'frontend'                => '',
            'label'                   => 'Exclude From Sitemap',
            'note'                    => 'Added by Magepow Sitemap',
            'input'                   => 'boolean',
            'class'                   => '',
            'source'                  => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
            'global'                  => ScopedAttributeInterface::SCOPE_GLOBAL,
            'visible'                 => true,
            'required'                => false,
            'user_defined'            => false,
            'default'                 => '',
            'searchable'              => false,
            'filterable'              => false,
            'comparable'              => false,
            'visible_on_front'        => false,
            'used_in_product_listing' => true,
            'unique'                  => false,
            'group'                   => 'Product Details',
            'sort_order'              => 100,
            'apply_to'                => '',
        ]);

        /**
         * Category attribute
         */
        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);

        $categorySetup->removeAttribute(Category::ENTITY, 'xml_sitemap_exclude');
        $categorySetup->addAttribute(Category::ENTITY, 'xml_sitemap_exclude', 
            [
                'type'      => 'int',
                'label'     => 'Exclude From Sitemap',
                'input'     => 'boolean',
                'sort_order' => 100,
                'source'    => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global'    => ScopedAttributeInterface::SCOPE_STORE,
                'visible'   => true,
                'required'  => false,
                'user_defined' => false,
                'default'   => null,
                'group'     => 'General Information',
                'backend'   => ''
            ]);

        $setup->endSetup();
    }
}
