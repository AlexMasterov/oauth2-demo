<?php

namespace OAuth2\Infrastructure\Configuration;

use Auryn\Injector;
use Equip\Configuration\ConfigurationInterface;
use Equip\Configuration\EnvTrait;
use Equip\Env;
use OAuth2\Application\Provider\ProviderConfig;
use OAuth2\Infrastructure\OAuth2Env;

final class OAuth2EnvConfiguration implements ConfigurationInterface
{
    use EnvTrait;

    const PREFIX = 'OAUTH2';

    public function apply(Injector $injector)
    {
        $providers = $this->providers($this->env);
        $options = [':values' => $providers];

        $injector->define(OAuth2Env::class, $options);
        $injector->define(ProviderConfig::class, $options);
    }

    private function providers(Env $env): array
    {
        $envOauth = $this->oauthEnv($env);

        $providers = [];
        foreach ($envOauth as $key => $value) {
            list(, $provider, $option) = explode('_', strtolower($key), 3);
            if ('provider' === $option) {
                $providers[$provider]['class'] = $value;
            } else {
                $providers[$provider]['options'][$this->camelCase($option)] = $value;
            }
        }

        return $providers;
    }

    private function oauthEnv(Env $env): array
    {
        $oauthFilter = static function ($value) {
            return stristr($value, self::PREFIX);
        };

        return array_filter($env->toArray(), $oauthFilter, ARRAY_FILTER_USE_KEY);
    }

    private function camelCase(string $value): string
    {
        $value = ucwords($value, '_');
        $value = str_replace('_', '', $value);

        return lcfirst($value);
    }
}
