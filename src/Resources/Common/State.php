<?php

namespace Hostville\Dorcas\Resources\Common;


use Hostville\Dorcas\Resources\AbstractResource;

class State extends AbstractResource
{
    /**
     * Returns the name of the resource.
     *
     * @return string
     */
    function getName(): string
    {
        return 'State';
    }
}