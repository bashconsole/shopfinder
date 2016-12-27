<?php
declare(strict_types=1);

namespace Bashconsole\Shopfinder\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * @api
 */
interface ShopfinderSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get shop list.
     *
     * @return \Bashconsole\Shopfinder\Api\Data\ShopfinderInterface[]
     */
    public function getItems();

    /**
     * Set shops list.
     *
     * @param \Bashconsole\Shopfinder\Api\Data\ShopfinderInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
