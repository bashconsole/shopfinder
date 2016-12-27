<?php
declare(strict_types=1);

// @codingStandardsIgnoreFile
namespace Bashconsole\Shopfinder\Model\ResourceModel\Stores\Grid;

use Magento\Framework\Api\AbstractServiceCollection;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SimpleDataObjectConverter;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Data\Collection\EntityFactory;
use Magento\Framework\DataObject;
use Bashconsole\Shopfinder\Api\ShopfinderRepositoryInterface;
use Bashconsole\Shopfinder\Api\Data\ShopfinderInterface;

/**
 * Shop collection backed by services
 */
class ServiceCollection extends AbstractServiceCollection
{
    /**
     * @var ShopfinderRepositoryInterface
     */
    public $shopfinderRepository;

    /**
     * @var SimpleDataObjectConverter
     */
    public $simpleDataObjectConverter;

    /**
     * @param EntityFactory $entityFactory
     * @param FilterBuilder $filterBuilder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param ShopfinderRepositoryInterface $shopfinderRepository
     * @param SimpleDataObjectConverter $simpleDataObjectConverter
     */
    public function __construct(
        EntityFactory $entityFactory,
        FilterBuilder $filterBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        ShopfinderRepositoryInterface $shopfinderRepository,
        SimpleDataObjectConverter $simpleDataObjectConverter
    ) {
        $this->shopfinderRepository          = $shopfinderRepository;
        $this->simpleDataObjectConverter = $simpleDataObjectConverter;
        parent::__construct($entityFactory, $filterBuilder, $searchCriteriaBuilder, $sortOrderBuilder);
    }

    /**
     * Load customer group collection data from service
     *
     * @param bool $printQuery
     * @param bool $logQuery
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function loadData($printQuery = false, $logQuery = false)
    {
        if (!$this->isLoaded()) {
            $searchCriteria = $this->getSearchCriteria();
            $searchResults = $this->shopfinderRepository->getList($searchCriteria);
            $this->_totalRecords = $searchResults->getTotalCount();
            /** @var ShopfinderInterface[] $shops */
            $shops = $searchResults->getItems();
            foreach ($shops as $shop) {
                $shopItem = new DataObject();
                $shopItem->addData(
                    $this->simpleDataObjectConverter->toFlatArray($shop, ShopfinderInterface::class)
                );
                $this->_addItem($shopItem);
            }
            $this->_setIsLoaded();
        }
        return $this;
    }
}
