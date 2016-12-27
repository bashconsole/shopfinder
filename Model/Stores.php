<?php
declare(strict_types=1);

namespace Bashconsole\Shopfinder\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Data\Collection\Db;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Bashconsole\Shopfinder\Api\Data\ShopfinderInterface;
use Bashconsole\Shopfinder\Model\Stores\Url;
use Bashconsole\Shopfinder\Model\ResourceModel\Stores as ShopfinderResourceModel;
use Bashconsole\Shopfinder\Model\Routing\RoutableInterface;
use Bashconsole\Shopfinder\Model\Source\AbstractSource;

/**
 * @method ShopfinderResourceModel _getResource()
 * @method ShopfinderResourceModel getResource()
 */
class Stores extends AbstractModel implements ShopfinderInterface, RoutableInterface
{
    /**
     * @var int
     */
    const STATUS_ENABLED = 1;
    /**
     * @var int
     */
    const STATUS_DISABLED = 0;
    /**
     * @var Url
     */
    public $urlModel;
    /**
     * cache tag
     *
     * @var string
     */
    const CACHE_TAG = 'bashconsole_shopfinder';

    /**
     * cache tag
     *
     * @var string
     */
    public $_cacheTag = 'bashconsole_shopfinder_stores';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    public $_eventPrefix = 'bashconsole_shopfinder_stores';

    /**
     * filter model
     *
     * @var \Magento\Framework\Filter\FilterManager
     */
    public $filter;

    /**
     * @var UploaderPool
     */
    public $uploaderPool;

    /**
     * @var \Bashconsole\Shopfinder\Model\Output
     */
    public $outputProcessor;

    /**
     * @var AbstractSource[]
     */
    public $optionProviders;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param Output $outputProcessor
     * @param UploaderPool $uploaderPool
     * @param FilterManager $filter
     * @param Url $urlModel
     * @param array $optionProviders
     * @param array $data
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Output $outputProcessor,
        UploaderPool $uploaderPool,
        FilterManager $filter,
        Url $urlModel,
        array $optionProviders = [],
        array $data = [],
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null
    ) {
        $this->outputProcessor = $outputProcessor;
        $this->uploaderPool    = $uploaderPool;
        $this->filter          = $filter;
        $this->urlModel        = $urlModel;
        $this->optionProviders = $optionProviders;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(ShopfinderResourceModel::class);
    }

//     /**
//      * Get type
//      *
//      * @return int
//      */
//     public function getType()
//     {
//         return $this->getData(ShopfinderInterface::TYPE);
//     }

    /**
     * @param $storeId
     * @return ShopfinderInterface
     */
    public function setStoreId($storeId)
    {
        $this->setData(ShopfinderInterface::STORE_ID, $storeId);
        return $this;
    }

    /**
     * @param $shopId
     * @return ShopfinderInterface
     */
    public function setShopId($shopId)
    {
        $this->setData(ShopfinderInterface::SHOP_ID, $shopId);
        return $this;
    }
    
    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->getData(ShopfinderInterface::COUNTRY);
    }

    /**
     * set name
     *
     * @param $name
     * @return ShopfinderInterface
     */
    public function setName($name)
    {
        return $this->setData(ShopfinderInterface::NAME, $name);
    }

//     /**
//      * set type
//      *
//      * @param $type
//      * @return ShopfinderInterface
//      */
//     public function setType($type)
//     {
//         return $this->setData(ShopfinderInterface::TYPE, $type);
//     }

    /**
     * Set country
     *
     * @param $country
     * @return ShopfinderInterface
     */
    public function setCountry($country)
    {
        return $this->setData(ShopfinderInterface::COUNTRY, $country);
    }
    
//         /**
//      * set link
//      *
//      * @param $link
//      * @return ShopfinderInterface
//      */
//     public function setLink($link)
//     {
//         return $this->setData(ShopfinderInterface::LINK, $link);
//     }


    /**
     * Set status
     *
     * @param $status
     * @return ShopfinderInterface
     */
    public function setStatus($status)
    {
        return $this->setData(ShopfinderInterface::STATUS, $status);
    }    
    
    /**
     * set image
     *
     * @param $image
     * @return ShopfinderInterface
     */
    public function setImage($image)
    {
        return $this->setData(ShopfinderInterface::IMAGE, $image);
    }

    /**
     * set created at
     *
     * @param $createdAt
     * @return ShopfinderInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(ShopfinderInterface::CREATED_AT, $createdAt);
    }

    /**
     * set updated at
     *
     * @param $updatedAt
     * @return ShopfinderInterface
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(ShopfinderInterface::UPDATED_AT, $updatedAt);
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(ShopfinderInterface::NAME);
    }

    /**
     * Get Shop ID
     *
     * @return string
     */
    public function getShopId()
    {
        return $this->getData(ShopfinderInterface::SHOP_ID);
    }

//     /**
//      * Get url key
//      *
//      * @return string
//      */
//     public function getLink()
//     {
//         return $this->getData(ShopfinderInterface::LINK);
//     }
    
    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->getData(ShopfinderInterface::IMAGE);
    }
    
    /**
     * @return bool|string
     * @throws LocalizedException
     */
    public function getImageUrl()
    {
        $url = false;
        $image = $this->getImage();
        if ($image) {
            if (is_string($image)) {
                $uploader = $this->uploaderPool->getUploader('image');
                $url = $uploader->getBaseUrl().$uploader->getBasePath().$image;
            } else {
                throw new LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }
        return $url;
    }


    /**
     * Get status
     *
     * @return bool|int
     */
    public function getStatus()
    {
        return $this->getData(ShopfinderInterface::STATUS);
    }


    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(ShopfinderInterface::CREATED_AT);
    }

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(ShopfinderInterface::UPDATED_AT);
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return array
     */
    public function getStoreId()
    {
        return $this->getData(ShopfinderInterface::STORE_ID);
    }

    /**
     * sanitize the url key
     *
     * @param $string
     * @return string
     */
    public function formatUrlKey($string)
    {
        return $this->filter->translitUrl($string);
    }

    /**
     * @return mixed
     */
    public function getShopUrl()
    {
        return $this->urlModel->getShopUrl($this);
    }

    /**
     * @return bool
     */
    public function status()
    {
        return (bool)$this->getStatus();
    }

    /**
     * @param $attribute
     * @return string
     */
    public function getAttributeText($attribute)
    {
        if (!isset($this->optionProviders[$attribute])) {
            return '';
        }
        if (!($this->optionProviders[$attribute] instanceof AbstractSource)) {
            return '';
        }
        return $this->optionProviders[$attribute]->getOptionText($this->getData($attribute));
    }
}
