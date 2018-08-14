<?php

namespace Hostville\Dorcas\Resources;


class Invite extends AbstractResource
{
    /**
     * @return $this
     */
    protected function prefillBody()
    {
        $this->addQueryArgument('client_id', $this->sdk->getClientId())
                ->addQueryArgument('client_secret', $this->sdk->getClientSecret());
        return $this;
    }
    
    /**
     * Returns the name of the resource.
     *
     * @return string
     */
    function getName(): string
    {
        return 'Invite';
    }
}