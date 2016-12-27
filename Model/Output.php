<?php
declare(strict_types=1);

namespace Bashconsole\Shopfinder\Model;

class Output
{
    /**
     * @var \Zend_Filter_Interface
     */
    public $templateProcessor;

    /**
     * @param \Zend_Filter_Interface $templateProcessor
     */
    public function __construct(
        \Zend_Filter_Interface $templateProcessor
    ) {
        $this->templateProcessor = $templateProcessor;
    }

    /**
     * @param $string
     * @return string
     */
    public function filterOutput($string)
    {
        return $this->templateProcessor->filter($string);
    }
}
