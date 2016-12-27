<?php
declare(strict_types=1);

namespace Bashconsole\Shopfinder\Controller\Adminhtml\Stores;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\Session;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Magento\Framework\View\Result\PageFactory;
use Bashconsole\Shopfinder\Api\ShopfinderRepositoryInterface;
use Bashconsole\Shopfinder\Api\Data\ShopfinderInterface;
use Bashconsole\Shopfinder\Api\Data\ShopfinderInterfaceFactory;
use Bashconsole\Shopfinder\Controller\Adminhtml\Stores as ShopfinderController;
use Bashconsole\Shopfinder\Model\Stores;
use Bashconsole\Shopfinder\Model\ResourceModel\Stores as ShopfinderResourceModel;

class InlineEdit extends ShopfinderController
{
    /**
     * @var DataObjectHelper
     */
    public $dataObjectHelper;
    /**
     * @var DataObjectProcessor
     */
    public $dataObjectProcessor;
    /**
     * @var JsonFactory
     */
    public $jsonFactory;
    /**
     * @var ShopfinderResourceModel
     */
    public $shopfinderResourceModel;

    /**
     * @param Registry $registry
     * @param ShopfinderRepositoryInterface $shopfinderRepository
     * @param PageFactory $resultPageFactory
     * @param Date $dateFilter
     * @param Context $context
     * @param DataObjectProcessor $dataObjectProcessor
     * @param DataObjectHelper $dataObjectHelper
     * @param JsonFactory $jsonFactory
     * @param ShopfinderResourceModel $shopfinderResourceModel
     */
    public function __construct(
        Registry $registry,
        ShopfinderRepositoryInterface $shopfinderRepository,
        PageFactory $resultPageFactory,
        Date $dateFilter,
        Context $context,
        DataObjectProcessor $dataObjectProcessor,
        DataObjectHelper $dataObjectHelper,
        JsonFactory $jsonFactory,
        ShopfinderResourceModel $shopfinderResourceModel
    ) {
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->dataObjectHelper    = $dataObjectHelper;
        $this->jsonFactory         = $jsonFactory;
        $this->shopfinderResourceModel = $shopfinderResourceModel;
        parent::__construct($registry, $shopfinderRepository, $resultPageFactory, $dateFilter, $context);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach (array_keys($postItems) as $shopfinderId) {
            /** @var \Bashconsole\Shopfinder\Model\Stores|ShopfinderInterface $shop */
            $shop = $this->shopfinderRepository->getById((int)$shopfinderId);
            try {
                $shopData = $this->filterData($postItems[$shopfinderId]);
                $this->dataObjectHelper->populateWithArray($shop, $shopData , ShopfinderInterface::class);
                $this->shopfinderResourceModel->saveAttribute($shop, array_keys($shopData));
            } catch (LocalizedException $e) {
                $messages[] = $this->getErrorWithShopId($shop, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithShopId($shop, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithShopId(
                    $shop,
                    __('Something went wrong while saving the shop.')
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add shop id to error message
     *
     * @param Stores $shop
     * @param string $errorText
     * @return string
     */
    public function getErrorWithShopId(Stores $shop, $errorText)
    {
        return '[Shop ID: ' . $shop->getId() . '] ' . $errorText;
    }
}
