<?php
declare(strict_types=1);

namespace Bashconsole\Shopfinder\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Bashconsole\Shopfinder\Api\ShopfinderRepositoryInterface;
use Bashconsole\Shopfinder\Api\Data;
use Bashconsole\Shopfinder\Api\Data\ShopfinderInterface;
use Bashconsole\Shopfinder\Api\Data\ShopfinderInterfaceFactory;
use Bashconsole\Shopfinder\Api\Data\ShopfinderSearchResultsInterfaceFactory;
use Bashconsole\Shopfinder\Model\ResourceModel\Stores as ResourceShopfinder;
use Bashconsole\Shopfinder\Model\ResourceModel\Stores\Collection;
use Bashconsole\Shopfinder\Model\ResourceModel\Stores\CollectionFactory as ShopfinderCollectionFactory;

/**
 * Class ShopfinderRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ShopfinderRepository implements ShopfinderRepositoryInterface
{
    /**
     * @var array
     */
    public $instances = [];
    /**
     * @var ResourceShopfinder
     */
    public $resource;
    /**
     * @var StoreManagerInterface
     */
    public $storeManager;
    /**
     * @var ShopfinderCollectionFactory
     */
    public $shopCollectionFactory;
    /**
     * @var ShopfinderSearchResultsInterfaceFactory
     */
    public $searchResultsFactory;
    /**
     * @var ShopfinderInterfaceFactory
     */
    public $shopfinderInterfaceFactory;
    /**
     * @var DataObjectHelper
     */
    public $dataObjectHelper;

    public function __construct(
        ResourceShopfinder $resource,
        StoreManagerInterface $storeManager,
        ShopfinderCollectionFactory $shopCollectionFactory,
        ShopfinderSearchResultsInterfaceFactory $shopSearchResultsInterfaceFactory,
        ShopfinderInterfaceFactory $shopfinderInterfaceFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->resource                 = $resource;
        $this->storeManager             = $storeManager;
        $this->shopCollectionFactory  = $shopCollectionFactory;
        $this->searchResultsFactory     = $shopSearchResultsInterfaceFactory;
        $this->shopfinderInterfaceFactory   = $shopfinderInterfaceFactory;
        $this->dataObjectHelper         = $dataObjectHelper;
    }
    /**
     * Save page.
     *
     * @param \Bashconsole\Shopfinder\Api\Data\ShopfinderInterface $shop
     * @return \Bashconsole\Shopfinder\Api\Data\ShopfinderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(ShopfinderInterface $shop)
    {
        /** @var ShopfinderInterface|\Magento\Framework\Model\AbstractModel $shop */
        if (empty($shop->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $shop->setStoreId($storeId);
        }
        try {
            $this->resource->save($shop);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the store: %1',
                $exception->getMessage()
            ));
        }
        return $shop;
    }

    /**
     * Retrieve Shop.
     *
     * @param int $shopfinderId
     * @return \Bashconsole\Shopfinder\Api\Data\ShopfinderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($shopfinderId)
    {
        if (!isset($this->instances[$shopfinderId])) {

            /** @var \Bashconsole\Shopfinder\Api\Data\ShopfinderInterface|\Magento\Framework\Model\AbstractModel $shop */
            $shop = $this->shopfinderInterfaceFactory->create();
            $this->resource->load($shop, $shopfinderId);
            
            if (!$shop->getId()) {
                throw new NoSuchEntityException(__('Requested shop doesn\'t exist'));

           }
            $this->instances[$shopfinderId] = $shop;
        }

        return $this->instances[$shopfinderId];;
    }

    /**
     * Retrieve pages matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Bashconsole\Shopfinder\Api\Data\ShopfinderSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Bashconsole\Shopfinder\Api\Data\ShopfinderSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \Bashconsole\Shopfinder\Model\ResourceModel\Stores\Collection $collection */
        $collection = $this->shopCollectionFactory->create();

        //Add filters from root filter group to the collection
        /** @var FilterGroup $group */
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
        $sortOrders = $searchCriteria->getSortOrders();
        /** @var SortOrder $sortOrder */
        if ($sortOrders) {
            foreach ($searchCriteria->getSortOrders() as $sortOrder) {
                $field = $sortOrder->getField();
                $collection->addOrder(
                    $field,
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        } else {
            // set a default sorting order since this method is used constantly in many
            // different blocks
            $field = 'entity_id';
            $collection->addOrder($field, 'ASC');
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        /** @var \Bashconsole\Shopfinder\Api\Data\ShopfinderInterface[] $shops */
        $shops = [];
        /** @var \Bashconsole\Shopfinder\Model\Stores $shop */
        foreach ($collection as $shop) {
            /** @var \Bashconsole\Shopfinder\Api\Data\ShopfinderInterface $shopDataObject */
            $shopDataObject = $this->shopfinderInterfaceFactory->create();
            $this->dataObjectHelper->populateWithArray($shopDataObject, $shop->getData(), ShopfinderInterface::class);
            $shops[] = $shopDataObject;
        }
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults->setItems($shops);
    }

    /**
     * Delete shop.
     *
     * @param \Bashconsole\Shopfinder\Api\Data\ShopfinderInterface $shop
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(ShopfinderInterface $shop)
    {
        /** @var \Bashconsole\Shopfinder\Api\Data\ShopfinderInterface|\Magento\Framework\Model\AbstractModel $shop */
        $id = $shop->getId();
        try {
            unset($this->instances[$id]);
            $this->resource->delete($shop);
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (\Exception $e) {
            throw new StateException(
                __('Unable to remove shop %1', $id)
            );
        }
        unset($this->instances[$id]);
        return true;
    }

    /**
     * Delete shop by ID.
     *
     * @param int $shopfinderId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($shopfinderId)
    {
        $shop = $this->getById($shopfinderId);
        return $this->delete($shop);
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param FilterGroup $filterGroup
     * @param Collection $collection
     * @return $this
     * @throws \Magento\Framework\Exception\InputException
     */
    public function addFilterGroupToCollection(FilterGroup $filterGroup, Collection $collection)
    {
        $fields = [];
        $conditions = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
            $fields[] = $filter->getField();
            $conditions[] = [$condition => $filter->getValue()];
        }
        if ($fields) {
            $collection->addFieldToFilter($fields, $conditions);
        }
        return $this;
    }

}
