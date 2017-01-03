<?php

namespace OAuth2\Application\Input;

use Equip\Adr\InputInterface;
use Equip\SessionInterface;
use OAuth2\Application\Provider\ProviderConfig;
use Psr\Http\Message\ServerRequestInterface;

class LoginProviderInput implements InputInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var ProviderConfig
     */
    private $config;

    public function __construct(
        SessionInterface $session,
        ProviderConfig $config
    ) {
        $this->session = $session;
        $this->config = $config;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $query = $request->getQueryParams();
        $cookies = $request->getCookieParams();
        $attributes = $request->getAttributes();

        return array_replace(
            $query,
            $cookies,
            $attributes
        );
    }

    private function state(string $provider)
    {
        $providerOptions = $this->config->getOptions($provider);
    }
}
