<?php

namespace OAuth2\Application\Action;

use Equip\Adr\PayloadInterface;
use Equip\Contract\ActionInterface;
use OAuth2\Application\Payload\Http\RedirectTrait;
use OAuth2\Application\Provider\ProviderFactoryInterface;

final class Connect implements ActionInterface
{
    use RedirectTrait;

    /**
     * @var ProviderFactoryInterface
     */
    private $factory;

    public function __construct(ProviderFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function __invoke(array $input): PayloadInterface
    {
        $url = $this->authorizationUrl($input);

        return $this->redirect($url);
    }

    private function authorizationUrl(array $input): string
    {
        $provider = $this->factory->create($input['provider']);

        return $provider->getAuthorizationUrl();
    }
}
