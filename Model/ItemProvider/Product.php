<?php
namespace Magepow\Sitemapexclusion\Model\ItemProvider;

use Magento\Sitemap\Model\ResourceModel\Catalog\ProductFactory;
use Magento\Sitemap\Model\SitemapItemInterfaceFactory;

class Product extends \Magento\Sitemap\Model\ItemProvider\Product
{
   
	/**
     * {@inheritdoc}
     */
     public function getItems($storeId)
    {   
        $items = parent::getItems($storeId);
         $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
         $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
         //remove product from sitmap
         $productId = $productCollection->addAttributeToSelect('xml_sitemap_exclude')
                                        ->addAttributeToFilter('xml_sitemap_exclude', '1');       
         foreach ($productId as $product){
            
             unset($items[$product->getId()]);
         }
       
        return $items;
    }
}