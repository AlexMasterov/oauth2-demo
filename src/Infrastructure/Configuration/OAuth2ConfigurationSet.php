<?php

namespace OAuth2\Infrastructure\Configuration;

use AlexMasterov\EquipTwig\Configuration\TwigResponderConfiguration;
use Equip\Configuration\ConfigurationSet;
use OAuth2\Infrastructure\Configuration\EnvConfiguration;
use OAuth2\Infrastructure\Configuration\OAuth2EnvConfiguration;
use OAuth2\Infrastructure\Configuration\OAuth2ProviderConfiguration;
use OAuth2\Infrastructure\Configuration\TwigConfiguration;

class OAuth2ConfigurationSet extends ConfigurationSet
{
    public function __construct()
    {
        parent::__construct([
            EnvConfiguration::class,
            TwigResponderConfiguration::class,
            TwigConfiguration::class,
            OAuth2EnvConfiguration::class,
            OAuth2ProviderConfiguration::class,
        ]);
    }
}
