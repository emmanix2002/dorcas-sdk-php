<?php

namespace Hostville\Dorcas\Resources\Invoicing;


use Hostville\Dorcas\Resources\AbstractResource;

class ProductCategory extends AbstractResource
{
    
    /**
     * Returns the name of the resource.
     *
     * @return string
     */
    function getName(): string
    {
        return 'ProductCategory';
    }
}