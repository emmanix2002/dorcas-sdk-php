<?php

namespace Dorcas\Resources\Crm;


use Dorcas\Resources\AbstractResource;

class ContactField extends AbstractResource
{

    /**
     * Returns the name of the resource.
     *
     * @return string
     */
    function getName(): string
    {
        return 'ContactField';
    }
}