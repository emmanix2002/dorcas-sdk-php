<?php

namespace Hostville\Dorcas\Resources\Common\Company;


use Hostville\Dorcas\Resources\AbstractResource;

class Department extends AbstractResource
{

    /**
     * Returns the name of the resource.
     *
     * @return string
     */
    function getName(): string
    {
        return 'Department';
    }
}