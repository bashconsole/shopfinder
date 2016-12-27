<?php
declare(strict_types=1);

namespace Bashconsole\Shopfinder\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Bashconsole\Shopfinder\Api\Data\ShopfinderInterface;

/**
 * @api
 */
interface ShopfinderRepositoryInterface
{
    /**
     * Save page.
     *
     * @param ShopfinderInterface $shop
     * @return ShopfinderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(ShopfinderInterface $shop);

    /**
     * Retrieve Shop.
     *
     * @param int $shopfinderId
     * @return ShopfinderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($shopfinderId);

    /**
     * Retrieve pages matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Bashconsole\Shopfinder\Api\Data\ShopfinderSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete shop.
     *
     * @param ShopfinderInterface $shop
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(ShopfinderInterface $shop);

    /**
     * Delete shop by ID.
     *
     * @param int $shopfinderId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($shopfinderId);
}
