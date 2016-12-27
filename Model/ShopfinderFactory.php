<?php
declare(strict_types=1);
 
namespace Bashconsole\Shopfinder\Model;

use Magento\Framework\ObjectManagerInterface;
use Bashconsole\Shopfinder\Model\Routing\RoutableInterface;

class ShopfinderFactory implements FactoryInterface
{
    /**
     * Object Manager instance
     *
     * @var ObjectManagerInterface
     */
    public $_objectManager = null;

    /**
     * Instance name to create
     *
     * @var string
     */
    public $_instanceName = null;

    /**
     * Factory constructor
     *
     * @param ObjectManagerInterface $objectManager
     * @param string $instanceName
     */
    public function __construct(ObjectManagerInterface $objectManager, $instanceName = Stores::class)
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName  = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return RoutableInterface|\Bashconsole\Shopfinder\Model\Stores
     */
    public function create(array $data = array())
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
