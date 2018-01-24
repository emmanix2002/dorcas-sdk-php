<?php

namespace Dorcas\Resources\Invoicing;


use Dorcas\Resources\AbstractResource;

class Order extends AbstractResource
{
    /**
     * Returns the name of the resource.
     *
     * @return string
     */
    function getName(): string
    {
        return 'Order';
    }
}