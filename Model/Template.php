<?php
declare(strict_types=1);
 
namespace Bashconsole\Shopfinder\Model;

class Template
{
	
    public function toOptionArray(): array
    {
        return array(
            array(
                'value' => 'full_width_sidebar',
                'label' => 'Full Width with Sidebar Options',
            ),
            array(
                'value' => 'page_width_sidebar',
                'label' => 'Page Width with Sidebar Options',
            ),
            array(
                'value' => 'page_width_top',
                'label' => 'Page Width with Top Options',
            )
        );
    }
    
}
