<?php

namespace Hostville\Dorcas\Services\Identity;


use Hostville\Dorcas\Services\AbstractService;

class Company extends AbstractService
{
    /**
     * @inheritdoc
     */
    public function requiresAuthorization(): bool
    {
        return true;
    }

    /**
     * Returns the name of the service.
     *
     * @return string
     */
    function getName(): string
    {
        return 'Company';
    }
}