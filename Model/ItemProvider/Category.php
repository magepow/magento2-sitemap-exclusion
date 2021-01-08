<?php
namespace Magepow\Sitemapexclusion\Model\ItemProvider;

use Magento\Sitemap\Model\ResourceModel\Catalog\CategoryFactory;
use Magento\Sitemap\Model\SitemapItemInterfaceFactory;

class Category extends \Magento\Sitemap\Model\ItemProvider\Category
{   
    
    public function getItems($storeId)
    {   

        $items = parent::getItems($storeId);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $CategoryFactory = $objectManager->create('Magento\Catalog\Model\ResourceModel\Category\CollectionFactory');
        $categories = $CategoryFactory->create()->addAttributeToSelect('sitemap_exclude')
                                                ->addAttributeToFilter('sitemap_exclude', '1');
  
        foreach ($categories as $category){
            unset($items[$category->getId()]);
            if ($this->getConfig()) {
               $categoriesChild = $this->getCategory($category->getId());
                if ($categoriesChild) {
                    foreach ($categoriesChild as $childCategories) {
                       
                        unset($items[$childCategories]);
                    }
                }
            }
            
        }
        return $items;
    }

    public function getConfig()
    {   
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $categoryFactory = $objectManager->create('\Magento\Framework\App\Config\ScopeConfigInterface');
        $exclusionChildCategory = $categoryFactory->getValue('sitemapexclusion/general/exclusion_child_category', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        return $exclusionChildCategory;
    }
    public function getCategory($categoryId){
        $categoriesId = array();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $CategoryFactory = $objectManager->create('Magento\Catalog\Model\Category');
        $subCategories = $CategoryFactory->getCategories($categoryId);
        foreach($subCategories as $subCategory) {
            $categoriesId[] = $subCategory->getId();
            // For Sub Categories
            if($subCategory->hasChildren()) {
                  $categoriesId = array_merge($categoriesId, $this->getCategory($subCategory->getId()));
            }
        }
        return $categoriesId;
    }

} 

