<?php

namespace Hostville\Dorcas\Resources;


class Plan extends AbstractResource
{
    /**
     * @inheritdoc
     */
    public function requiresAuthorization(): bool
    {
        return false;
    }

    /**
     * Returns the name of the resource.
     *
     * @return string
     */
    function getName(): string
    {
        return 'Plan';
    }
}