<?php
declare(strict_types=1);

namespace Bashconsole\Shopfinder\Model;

use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Exception\LocalizedException;

class UploaderPool
{
    /**
     * @var ObjectManagerInterface
     */
    public $objectManager;
    /**
     * @var array
     */
    public $uploaders;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param array $uploaders
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        array $uploaders = []
    ) {
        $this->objectManager = $objectManager;
        $this->uploaders     = $uploaders;
    }

    /**
     * @param $type
     * @return Uploader
     * @throws \Exception
     */
    public function getUploader($type)
    {
        if (!isset($this->uploaders[$type])) {
            throw new \Magento\Framework\Exception\LocalizedException(
	            __("Uploader not found for type: ".$type)
            );
        }
        if (!is_object($this->uploaders[$type])) {
            $this->uploaders[$type] = $this->objectManager->create($this->uploaders[$type]);

        }
        $uploader = $this->uploaders[$type];
        if (!($uploader instanceof Uploader)) {	        
            throw new \Magento\Framework\Exception\LocalizedException(
	            __("Uploader for type {$type} not instance of ". Uploader::class)
            );
        }
        return $uploader;
    }
}
