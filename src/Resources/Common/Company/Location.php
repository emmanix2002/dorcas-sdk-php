<?php

namespace Hostville\Dorcas\Resources\Common\Company;


use Hostville\Dorcas\Resources\AbstractResource;

class Location extends AbstractResource
{

    /**
     * Returns the name of the resource.
     *
     * @return string
     */
    function getName(): string
    {
        return 'Location';
    }
}