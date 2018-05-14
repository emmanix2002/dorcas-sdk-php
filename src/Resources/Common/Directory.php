<?php

namespace Hostville\Dorcas\Resources\Common;


use Hostville\Dorcas\Resources\AbstractResource;

class Directory extends AbstractResource
{
    
    /**
     * Returns the name of the resource.
     *
     * @return string
     */
    function getName(): string
    {
        return 'Directory';
    }
}