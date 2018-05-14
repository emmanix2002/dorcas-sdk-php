<?php

namespace Hostville\Dorcas\Services\Identity;


use Hostville\Dorcas\Services\AbstractService;

class Profile extends AbstractService
{
    /**
     * @inheritdoc
     */
    public function requiresAuthorization(): bool
    {
        return true;
    }

    /**
     * Returns the name of the resource.
     *
     * @return string
     */
    function getName(): string
    {
        return 'Profile';
    }
}