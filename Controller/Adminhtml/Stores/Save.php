<?php
declare(strict_types=1);

namespace Bashconsole\Shopfinder\Controller\Adminhtml\Stores;

use Magento\Backend\Model\Session;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Magento\Framework\View\Result\PageFactory;
use Bashconsole\Shopfinder\Api\ShopfinderRepositoryInterface;
use Bashconsole\Shopfinder\Api\Data\ShopfinderInterface;
use Bashconsole\Shopfinder\Api\Data\ShopfinderInterfaceFactory;
use Bashconsole\Shopfinder\Controller\Adminhtml\Stores;
use Bashconsole\Shopfinder\Model\Uploader;
use Bashconsole\Shopfinder\Model\UploaderPool;

class Save extends Stores
{
    /**
     * @var DataObjectProcessor
     */
    public $dataObjectProcessor;

    /**
     * @var DataObjectHelper
     */
    public $dataObjectHelper;

    /**
     * @var UploaderPool
     */
    public $uploaderPool;

    /**
     * @param Registry $registry
     * @param ShopfinderRepositoryInterface $shopfinderRepository
     * @param PageFactory $resultPageFactory
     * @param Date $dateFilter
     * @param Context $context
     * @param ShopfinderInterfaceFactory $shopFactory
     * @param DataObjectProcessor $dataObjectProcessor
     * @param DataObjectHelper $dataObjectHelper
     * @param UploaderPool $uploaderPool
     */
    public function __construct(
        Registry $registry,
        ShopfinderRepositoryInterface $shopfinderRepository,
        PageFactory $resultPageFactory,
        Date $dateFilter,
        Context $context,
        ShopfinderInterfaceFactory $shopFactory,
        DataObjectProcessor $dataObjectProcessor,
        DataObjectHelper $dataObjectHelper,
        UploaderPool $uploaderPool
    ) {
        $this->shopFactory = $shopFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->uploaderPool = $uploaderPool;
        parent::__construct($registry, $shopfinderRepository, $resultPageFactory, $dateFilter, $context);
    }

    /**
     * run the action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {

        /** @var \Bashconsole\Shopfinder\Api\Data\ShopfinderInterface $shop */
        $shop = null;
        $data = $this->getRequest()->getPostValue();
        $id = !empty($data['entity_id']) ? $data['entity_id'] : null;
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            if ($id) {
                $shop = $this->shopfinderRepository->getById((int)$id);
            } else {
                unset($data['entity_id']);
                $shop = $this->shopFactory->create();
            }
            $image = $this->getUploader('image')->uploadFileAndGetName('image', $data);
            $data['image'] = $image;
            $this->dataObjectHelper->populateWithArray($shop, $data, ShopfinderInterface::class);
            $this->shopfinderRepository->save($shop);
            $this->messageManager->addSuccessMessage(__('You saved the store'));
            if ($this->getRequest()->getParam('back')) {
                $resultRedirect->setPath('shopfinder/stores/edit', ['entity_id' => $shop->getId()]);
            } else {
                $resultRedirect->setPath('shopfinder/stores');
            }
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            if ($shop != null) {
                $this->storeShopfinderDataToSession(
                    $this->dataObjectProcessor->buildOutputDataArray(
                        $shop,
                        ShopfinderInterface::class
                    )
                );
            }
            $resultRedirect->setPath('shopfinder/stores/edit', ['entity_id' => $id]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('There was a problem saving the store'));
            if ($shop != null) {
                $this->storeShopfinderDataToSession(
                    $this->dataObjectProcessor->buildOutputDataArray(
                        $shop,
                        ShopfinderInterface::class
                    )
                );
            }
            $resultRedirect->setPath('shopfinder/stores/edit', ['store_id' => $id]);
        }
        return $resultRedirect;
    }

    /**
     * @param $type
     * @return Uploader
     * @throws \Exception
     */
    public function getUploader($type)
    {
        return $this->uploaderPool->getUploader($type);
    }

    /**
     * @param $shopData
     */
    public function storeShopfinderDataToSession($shopData)
    {
        $this->_getSession()->setBashconsoleShopfinderStoresData($shopData);
    }
}
