<?php

namespace Hostville\Dorcas\Resources\ECommerce;


use Hostville\Dorcas\Resources\AbstractResource;

class Blog extends AbstractResource
{
    
    /**
     * Returns the name of the resource.
     *
     * @return string
     */
    function getName(): string
    {
        return 'Blog';
    }
}