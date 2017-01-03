<?php

namespace OAuth2\Application\Provider;

use Equip\SessionInterface;
use OAuth2\Application\Provider\ProviderList;

final class ProviderConnectedService
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var ProviderList
     */
    private $providerList;

    public function __construct(
        SessionInterface $session,
        ProviderList $providerList
    ) {
        $this->session = $session;
        $this->providerList = $providerList;
    }

    public function __invoke(): array
    {
        $tokens = $this->session->get('tokens', []);
        $providers = $this->providerList->toArray();

        $available = array_fill_keys(array_values($providers), false);
        $connected = array_fill_keys(array_keys(array_filter($tokens)), true);

        return array_replace($available, $connected);
    }
}
