<?php
declare(strict_types=1);

namespace Bashconsole\Shopfinder\Block\Adminhtml\Stores\Edit\Buttons;

use Magento\Backend\Block\Widget\Context;
use Bashconsole\Shopfinder\Api\ShopfinderRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Generic
{
    /**
     * @var Context
     */
    public $context;

    /**
     * @var ShopfinderRepositoryInterface
     */
    public $shopfinderRepository;

    /**
     * @param Context $context
     * @param ShopfinderRepositoryInterface $shopfinderRepository
     */
    public function __construct(
        Context $context,
        ShopfinderRepositoryInterface $shopfinderRepository
    ) {
        $this->context = $context;
        $this->shopfinderRepository = $shopfinderRepository;
    }

    /**
     * Return Shop page ID
     *
     * @return int|null
     */
    public function getShopfinderId()
    {
        try {
            return $this->shopfinderRepository->getById(
                $this->context->getRequest()->getParam('entity_id')
            )->getId();
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
