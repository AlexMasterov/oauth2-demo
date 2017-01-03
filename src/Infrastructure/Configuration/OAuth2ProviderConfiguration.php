<?php

namespace OAuth2\Infrastructure\Configuration;

use Auryn\Injector;
use Equip\Configuration\ConfigurationInterface;
use OAuth2\Application\Provider\ProviderFactory;
use OAuth2\Application\Provider\ProviderFactoryInterface;
use OAuth2\Application\Provider\ProviderList;
use OAuth2\Infrastructure\OAuth2Env;

class OAuth2ProviderConfiguration implements ConfigurationInterface
{
    /**
     * @var OAuth2Env
     */
    private $env;

    public function __construct(OAuth2Env $env)
    {
        $this->env = $env;
    }

    public function apply(Injector $injector)
    {
        $providers = $this->env->toArray();

        foreach ($providers as $provider) {
            if (isset($provider['options'])) {
                $injector->define($provider['class'], [
                    ':options' => $provider['options'],
                ]);
            }
        }

        $injector->prepare(
            ProviderList::class,
            static function (ProviderList $env) use ($providers) {
                return $env->withValues(array_keys($providers));
            }
        );

        $injector->define(ProviderFactory::class, [
            ':providers' => $providers,
        ]);

        $injector->alias(
           ProviderFactoryInterface::class,
           ProviderFactory::class
        );
    }
}
