<?php

namespace Hostville\Dorcas\Services\Identity;


use Hostville\Dorcas\Services\AbstractService;

class Registration extends AbstractService
{
    /**
     * @return $this
     */
    protected function prefillBody()
    {
        $this->body['client_id'] = $this->sdk->getClientId();
        $this->body['client_secret'] = $this->sdk->getClientSecret();
        return $this;
    }

    /**
     * Returns the name of the resource.
     *
     * @return string
     */
    function getName(): string
    {
        return 'Registration';
    }
}