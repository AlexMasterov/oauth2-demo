<?php

namespace OAuth2\Infrastructure\Configuration;

use Auryn\Injector;
use Equip\Configuration\ConfigurationInterface;
use Equip\Env;
use josegonzalez\Dotenv\Loader;

final class EnvConfiguration implements ConfigurationInterface
{
    const FILE = '.env';

    /**
     * @var string
     */
    private $rootDir;

    public function __construct(string $rootDir = null)
    {
        $this->rootDir = $rootDir ?? dirname(__DIR__, 3);
    }

    public function apply(Injector $injector)
    {
        $injector->share(Env::class);

        $injector->prepare(Env::class, [$this, 'prepareEnv']);
    }

    public function prepareEnv(Env $env): Env
    {
        $envFile = $this->rootDir . DIRECTORY_SEPARATOR . self::FILE;

        $values = $this->loader($envFile)->toArray();

        return $env->withValues($values);
    }

    private function loader(string $envFile): Loader
    {
        $rootFilter = function (array $data) {
            return str_replace('__ROOT__', $this->rootDir, $data);
        };

        return (new Loader($envFile))
            ->setFilters([$rootFilter])
            ->parse()
            ->filter();
    }
}
