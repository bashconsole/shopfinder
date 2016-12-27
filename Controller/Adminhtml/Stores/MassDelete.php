<?php
declare(strict_types=1);

namespace Bashconsole\Shopfinder\Controller\Adminhtml\Stores;

use Bashconsole\Shopfinder\Model\Stores;

class MassDelete extends MassAction
{
    /**
     * @param Stores $shop
     * @return $this
     */
    public function massAction(Stores $shop)
    {
        $this->shopfinderRepository->delete($shop);
        return $this;
    }
}
