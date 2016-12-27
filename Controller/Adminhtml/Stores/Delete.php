<?php
declare(strict_types=1);

namespace Bashconsole\Shopfinder\Controller\Adminhtml\Stores;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Bashconsole\Shopfinder\Controller\Adminhtml\Stores;

class Delete extends Stores
{
    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('entity_id');
        if ($id) {
            try {
                $this->shopfinderRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('The shop has been deleted.'));
                $resultRedirect->setPath('shopfinder/*/');
                return $resultRedirect;
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('The shop no longer exists.'));
                return $resultRedirect->setPath('shopfinder/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('shopfinder/stores/edit', ['entity_id' => $id]);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('There was a problem deleting the shop'));
                return $resultRedirect->setPath('shopfinder/stores/edit', ['entity_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a shop to delete.'));
        $resultRedirect->setPath('shopfinder/*/');
        return $resultRedirect;
    }
}
