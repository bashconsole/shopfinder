<?php
declare(strict_types=1);

namespace Bashconsole\Shopfinder\Controller\Adminhtml\Stores;

use Bashconsole\Shopfinder\Controller\Adminhtml\Stores;
use Bashconsole\Shopfinder\Controller\RegistryConstants;

class Edit extends Stores
{
    /**
     * Initialize current shop and set it in the registry.
     *
     * @return int
     */
    public function _initShop()
    {
        $shopfinderId = $this->getRequest()->getParam('entity_id');
        $this->coreRegistry->register(RegistryConstants::CURRENT_STOCKIST_ID, $shopfinderId);

        return $shopfinderId;
    }

    /**
     * Edit or create shop
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $shopfinderId = $this->_initShop();
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Bashconsole_Shopfinder::stores');
        $resultPage->getConfig()->getTitle()->prepend(__('Shopfinder'));
        $resultPage->addBreadcrumb(__('Shopfinder'), __('Shopfinder'), $this->getUrl('shopfinder/stores'));

        if ($shopfinderId === null) {
            $resultPage->addBreadcrumb(__('New Store'), __('New Store'));
            $resultPage->getConfig()->getTitle()->prepend(__('New Store'));
        } else {
            $resultPage->addBreadcrumb(__('Edit Store'), __('Edit Store'));
            $resultPage->getConfig()->getTitle()->prepend(
                $this->shopfinderRepository->getById($shopfinderId)->getName()
            );
        }
        return $resultPage;
    }
}
