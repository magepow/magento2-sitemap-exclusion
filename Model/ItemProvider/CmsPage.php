<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magepow\Sitemapexclusion\Model\ItemProvider;

use Magento\Sitemap\Model\ResourceModel\Cms\PageFactory;
use Magento\Sitemap\Model\SitemapItemInterfaceFactory;

class CmsPage extends \Magento\Sitemap\Model\ItemProvider\CmsPage
{
    /**
     * {@inheritdoc}
     */
    public function getItems($storeId)
    {   
        $items = parent::getItems($storeId);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $collection = $objectManager->get('\Magento\Cms\Model\ResourceModel\Page\CollectionFactory');
         // add Filter if you want 
        $cmsPage = $collection->create()->addFieldToFilter('xml_sitemap_exclude','1');   
        foreach ($cmsPage as $page){
             unset($items[$page->getId()]);
        }
        return $items;
    }
}
