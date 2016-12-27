<?php
declare(strict_types=1);

namespace Bashconsole\Shopfinder\Controller\Adminhtml\Stores;

use \Bashconsole\Shopfinder\Controller\Adminhtml\Stores as ShopfinderController;

class Index extends ShopfinderController
{
    /**
     * Shopfinder list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Bashconsole_Shopfinder::stores');
        $resultPage->getConfig()->getTitle()->prepend(__('Shopfinder'));
        $resultPage->addBreadcrumb(__('Shopfinder'), __('Shopfinder'));
        return $resultPage;
    }
}
