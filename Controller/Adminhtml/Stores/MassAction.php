<?php
declare(strict_types=1);

namespace Bashconsole\Shopfinder\Controller\Adminhtml\Stores;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Bashconsole\Shopfinder\Api\ShopfinderRepositoryInterface;
use Bashconsole\Shopfinder\Controller\Adminhtml\Stores;
use Bashconsole\Shopfinder\Model\Stores as ShopfinderModel;
use Bashconsole\Shopfinder\Model\ResourceModel\Stores\CollectionFactory;

abstract class MassAction extends Stores
{
    /**
     * @var Filter
     */
    public $filter;
    /**
     * @var CollectionFactory
     */
    public $collectionFactory;
    /**
     * @var string
     */
    public $successMessage;
    /**
     * @var string
     */
    public $errorMessage;

    /**
     * @param Registry $registry
     * @param ShopfinderRepositoryInterface $shopfinderRepository
     * @param PageFactory $resultPageFactory
     * @param Date $dateFilter
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param $successMessage
     * @param $errorMessage
     */
    public function __construct(
        Registry $registry,
        ShopfinderRepositoryInterface $shopfinderRepository,
        PageFactory $resultPageFactory,
        Date $dateFilter,
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        $successMessage,
        $errorMessage
    ) {
        $this->filter            = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->successMessage    = $successMessage;
        $this->errorMessage      = $errorMessage;
        parent::__construct($registry, $shopfinderRepository, $resultPageFactory, $dateFilter, $context);
    }

    /**
     * @param ShopfinderModel $shop
     * @return mixed
     */
    public abstract function massAction(ShopfinderModel $shop);

    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $collectionSize = $collection->getSize();
            foreach ($collection as $shop) {
                $this->massAction($shop);
            }
            $this->messageManager->addSuccessMessage(__($this->successMessage, $collectionSize));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __($this->errorMessage));
        }
        $redirectResult = $this->resultRedirectFactory->create();
        $redirectResult->setPath('bashconsole_shopfinder/*/index');
        return $redirectResult;
    }
}
