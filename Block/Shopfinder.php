<?php
declare(strict_types=1);
 
namespace Bashconsole\Shopfinder\Block;

use Magento\Backend\Block\Template\Context;
use Bashconsole\Shopfinder\Model\Stores;
use Bashconsole\Shopfinder\Model\ResourceModel\Stores\CollectionFactory as ShopfinderCollectionFactory;
use Bashconsole\Shopfinder\Model\ResourceModel\Stores\Collection;
use Magento\Directory\Model\CountryFactory;
use Magento\Directory\Model\Config\Source\Country;
use Magento\Store\Model\ScopeInterface;

class Shopfinder extends \Magento\Framework\View\Element\Template
{
   
    /**
     * @var ShopfinderCollectionFactory
     */
    public $shopfinderCollectionFactory;
        
    /**
     * @var Country
     */
    public $countryHelper;
    
    public function __construct(
        ShopfinderCollectionFactory $shopfinderCollectionFactory,
        Country $countryHelper,
        Context $context,
        array $data = []
    ) {
        $this->shopfinderCollectionFactory = $shopfinderCollectionFactory;
        $this->countryHelper = $countryHelper;
        parent::__construct($context, $data);
    }
    
     /**
      * return shops collection
      *
      * @return CollectionFactory
      */
    public function getStoresForFrontend(): Collection
    {
        $collection = $this->shopfinderCollectionFactory->create()
            ->addFieldToSelect('*')
            ->addFieldToFilter('status', Stores::STATUS_ENABLED)
            ->addStoreFilter($this->_storeManager->getStore()->getId())
            ->setOrder('name', 'ASC');
        return $collection;
    }
    
    /**
     * get an array of country codes and country names: AF => Afganisthan
     *
     * @return array
     */
    public function getCountries(): array
    {

        $loadCountries = $this->countryHelper->toOptionArray();
        $countries = [];
        $i = 0;
        foreach ($loadCountries as $country ) {
            $i++;
            if ($i == 1) { //remove first element that is a select
                continue;
            }
            $countries[$country["value"]] = $country["label"];
        }
        return $countries;
    }
    
    
     /**
     * get base image url
     *
     * @return string
     */ 
    public function getBaseImageUrl(): string
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }
    
}