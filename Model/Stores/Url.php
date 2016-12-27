<?php
declare(strict_types=1);

namespace Bashconsole\Shopfinder\Model\Stores;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Bashconsole\Shopfinder\Model\Stores;

class Url
{
    /**
     * @var string
     */
    const URL_CONFIG_PATH      = 'bashconsole_shopfinder/shopfinder_content/url';

    /**
     * url builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    public $urlBuilder;

    /**
     * @var ScopeConfigInterface
     */
    public $scopeConfig;

    /**
     * @param UrlInterface $urlBuilder
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        UrlInterface $urlBuilder,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return string
     */
    public function getListUrl()
    {
        $sefUrl = $this->scopeConfig->getValue(self::URL_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
        if ($sefUrl) {
            return $this->urlBuilder->getUrl('', ['_direct' => $sefUrl]);
        }
        return $this->urlBuilder->getUrl('shopfinder/stores/index');
    }

    /**
     * @param Stores $shop
     * @return string
     */
    public function getShopUrl(Stores $shop)
    {
        if ($urlKey = $shop->getUrlKey()) {
            return $this->urlBuilder->getUrl('', ['_direct'=>$urlKey]);
        }
        return $this->urlBuilder->getUrl('shopfinder/stores/view', ['id' => $shop->getId()]);
    }
}
