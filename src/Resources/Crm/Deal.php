<?php

namespace Hostville\Dorcas\Resources\Crm;


use Hostville\Dorcas\Resources\AbstractResource;

class Deal extends AbstractResource
{
    
    /**
     * Returns the name of the resource.
     *
     * @return string
     */
    function getName(): string
    {
        return 'Deal';
    }
}