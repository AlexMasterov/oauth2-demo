<?php

namespace OAuth2\Infrastructure\Configuration;

use AlexMasterov\TwigExtension\Psr7UriExtension;
use Auryn\Injector;
use Equip\Configuration\ConfigurationInterface;
use Equip\Configuration\EnvTrait;
use Equip\Env;
use RuntimeException;
use Twig_Environment;

class TwigConfiguration implements ConfigurationInterface
{
    use EnvTrait;

    public function apply(Injector $injector)
    {
        $injector->prepare(Twig_Environment::class, [$this, 'prepareTwig']);
    }

    public function prepareTwig(
        Twig_Environment $twig,
        Injector $injector
    ): Twig_Environment
    {
        $assets = $this->assets($this->env);

        $twig->addGlobal('assets', $assets);
        $twig->addExtension(
            $injector->make(Psr7UriExtension::class)
        );

        return $twig;
    }

    private function assets(Env $env)
    {
        $assetsPath = $env->getValue('TWIG_ASSETS_PATH', 'assets.json');

        if (false === $assets = @file_get_contents($assetsPath)) {
            throw new RuntimeException('Asserts could not be loaded.');
        }

        return json_decode($assets);
    }
}
