<?php

namespace Hostville\Dorcas\Resources;


class Partner extends AbstractResource
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
        return 'Partner';
    }
}