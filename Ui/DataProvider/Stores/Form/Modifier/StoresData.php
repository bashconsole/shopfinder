<?php
declare(strict_types=1);

namespace Bashconsole\Shopfinder\Ui\DataProvider\Stores\Form\Modifier;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Bashconsole\Shopfinder\Model\ResourceModel\Stores\CollectionFactory;

class StoresData implements ModifierInterface
{
    /**
     * @var \Bashconsole\Shopfinder\Model\ResourceModel\Stores\Collection
     */
    public $collection;

    /**
     * @param CollectionFactory $shopCollectionFactory
     */
    public function __construct(
        CollectionFactory $shopCollectionFactory
    ) {
        $this->collection = $shopCollectionFactory->create();
    }

    /**
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }

    /**
     * @param array $data
     * @return array|mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function modifyData(array $data)
    {
        $items = $this->collection->getItems();
        /** @var $shop \Bashconsole\Shopfinder\Model\Stores */
        foreach ($items as $shop) {
            $_data = $shop->getData();
            if (isset($_data['image'])) {
                $image = [];
                $image[0]['name'] = $shop->getImage();
                $image[0]['url'] = $shop->getImageUrl();
                $_data['image'] = $image;
            }
            $shop->setData($_data);
            $data[$shop->getId()] = $_data;
        }
        return $data;
    }
}
