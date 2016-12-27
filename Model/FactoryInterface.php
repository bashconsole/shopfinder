<?php
declare(strict_types=1);

namespace Bashconsole\Shopfinder\Model;

use Bashconsole\Shopfinder\Model\Routing\RoutableInterface;

interface FactoryInterface
{
    /**
     * @return RoutableInterface
     */
    public function create();
}
