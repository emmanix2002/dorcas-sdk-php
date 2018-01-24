<?php

namespace Dorcas\Resources\Invoicing;


use Dorcas\Resources\AbstractResource;

class Product extends AbstractResource
{

    /**
     * Returns the name of the resource.
     *
     * @return string
     */
    function getName(): string
    {
        return 'Product';
    }
}