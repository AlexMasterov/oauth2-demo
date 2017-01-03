<?php

namespace OAuth2\Application\Action;

use Equip\Adr\PayloadInterface;
use Equip\Contract\ActionInterface;
use OAuth2\Application\Payload\RenderTrait;
use OAuth2\Application\Provider\ProviderConnectedService;

final class Welcome implements ActionInterface
{
    use RenderTrait;

    /**
     * @var ProviderConnectedService
     */
    private $providerConnected;

    public function __construct(ProviderConnectedService $providerConnected)
    {
        $this->providerConnected = $providerConnected;
    }

    public function __invoke(array $input): PayloadInterface
    {
        $providers = ($this->providerConnected)();

        return $this->render('index', compact('providers'));
    }
}
