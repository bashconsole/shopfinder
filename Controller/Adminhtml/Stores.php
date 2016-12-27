<?php
declare(strict_types=1);

namespace Bashconsole\Shopfinder\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Magento\Framework\View\Result\PageFactory;
use Bashconsole\Shopfinder\Api\ShopfinderRepositoryInterface;

abstract class Stores extends Action
{
    /**
     * @var string
     */
    const ACTION_RESOURCE = 'Bashconsole_Shopfinder::stores';
    /**
     * shop factory
     *
     * @var ShopfinderRepositoryInterface
     */
    public $shopfinderRepository;

    /**
     * Core registry
     *
     * @var Registry
     */
    public $coreRegistry;

    /**
     * date filter
     *
     * @var \Magento\Framework\Stdlib\DateTime\Filter\Date
     */
    public $dateFilter;

    /**
     * @var PageFactory
     */
    public $resultPageFactory;

    /**
     * @param Registry $registry
     * @param ShopfinderRepositoryInterface $shopfinderRepository
     * @param PageFactory $resultPageFactory
     * @param Date $dateFilter
     * @param Context $context
     */
    public function __construct(
        Registry $registry,
        ShopfinderRepositoryInterface $shopfinderRepository,
        PageFactory $resultPageFactory,
        Date $dateFilter,
        Context $context

    ) {
        $this->coreRegistry      = $registry;
        $this->shopfinderRepository  = $shopfinderRepository;
        $this->resultPageFactory = $resultPageFactory;
        $this->dateFilter        = $dateFilter;
        parent::__construct($context);
    }

    /**
     * filter dates
     *
     * @param array $data
     * @return array
     */
    public function filterData($data)
    {
        $inputFilter = new \Zend_Filter_Input(
            [],
            $data
        );
        $data = $inputFilter->getUnescaped();
        
        return $data;
    }

}
