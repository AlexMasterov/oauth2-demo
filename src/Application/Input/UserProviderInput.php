<?php

namespace OAuth2\Application\Input;

use Equip\Adr\InputInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserProviderInput implements InputInterface
{
    public function __invoke(ServerRequestInterface $request)
    {
        $provider = $this->provider($request);
        $token    = $this->token($provider);

        return compact('provider', 'token');
    }

    private function provider(ServerRequestInterface $request): string
    {
        $attributes = $request->getAttributes();

        return $attributes['provider'];
    }

    private function token(string $provider)
    {
        return [];
    }
}
