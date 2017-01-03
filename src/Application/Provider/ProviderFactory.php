<?php

namespace OAuth2\Application\Provider;

use Equip\Resolver\ResolverTrait;
use League\OAuth2\Client\Provider\AbstractProvider;
use OAuth2\Application\Provider\ProviderConfig;
use OAuth2\Application\Provider\ProviderFactoryInterface;
use Relay\ResolverInterface;
use RuntimeException;

class ProviderFactory implements ProviderFactoryInterface
{
    use ResolverTrait;

    /**
     * @var ProviderConfig
     */
    private $config;

    public function __construct(
        ResolverInterface $resolver,
        ProviderConfig $config
    ) {
        $this->resolver = $resolver;
        $this->config = $config;
    }

    public function create(string $provider): AbstractProvider
    {
        $providerClass = $this->config->getClass($provider);

        if (empty($providerClass)) {
            throw new RuntimeException(sprintf(
                'Provider `%s` has not been defined, check your configuration',
                $provider
            ));
        }

        return $this->resolve($providerClass);
    }
}
