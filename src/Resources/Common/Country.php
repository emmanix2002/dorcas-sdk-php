<?php

namespace Hostville\Dorcas\Resources\Common;


use Hostville\Dorcas\Resources\AbstractResource;

class Country extends AbstractResource
{

    /**
     * Returns the name of the resource.
     *
     * @return string
     */
    function getName(): string
    {
        return 'Country';
    }
}