<?php

namespace Hostville\Dorcas\Services\Identity;


use Hostville\Dorcas\Services\AbstractService;

class PasswordLogin extends AbstractService
{
    /**
     * @inheritdoc
     */
    public function isJsonRequest(): bool
    {
        return false;
    }

    /**
     * @return $this
     */
    protected function prefillBody()
    {
        $this->body['client_id'] = $this->sdk->getClientId();
        $this->body['client_secret'] = $this->sdk->getClientSecret();
        $this->body['grant_type'] = 'password';
        $this->body['scope'] = '*';
        return $this;
    }

    /**
     * Returns the name of the resource.
     *
     * @return string
     */
    function getName(): string
    {
        return 'PasswordLogin';
    }
}