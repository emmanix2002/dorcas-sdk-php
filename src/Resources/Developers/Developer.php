<?php

namespace Hostville\Dorcas\Resources\Developers;


use Hostville\Dorcas\Resources\AbstractResource;

class Developer extends AbstractResource
{
    
    /**
     * Returns the name of the resource.
     *
     * @return string
     */
    function getName(): string
    {
        return 'Developer';
    }
}