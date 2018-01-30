<?php

namespace Hostville\Dorcas\Resources\Users;


use Hostville\Dorcas\Resources\AbstractResource;

class User extends AbstractResource
{

    /**
     * Returns the name of the resource.
     *
     * @return string
     */
    function getName(): string
    {
        return 'User';
    }
}