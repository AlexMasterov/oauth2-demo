<?php

namespace OAuth2\Application\Provider;

use Equip\Structure\Dictionary;

class ProviderConfig extends Dictionary
{
    public function getClass(string $provider): string
    {
        return $this->getParam($provider, 'class');
    }

    public function getOptions(string $provider): array
    {
        return $this->getParam($provider, 'options');
    }

    private function getParam(string $provider, string $option)
    {
        if (isset($this->values[$provider][$option])) {
            return $this->values[$provider][$option];
        }

        return null;
    }
}
