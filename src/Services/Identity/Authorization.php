<?php

namespace Hostville\Dorcas\Services\Identity;


use Hostville\Dorcas\Services\AbstractService;

class Authorization extends AbstractService
{
    
    /**
     * Returns the name of the resource.
     *
     * @return string
     */
    function getName(): string
    {
        return 'Authorization';
    }
}