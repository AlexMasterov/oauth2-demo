<?php

namespace OAuth2\Application\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;

interface ProviderFactoryInterface
{
    public function create(string $provider): AbstractProvider;
}
