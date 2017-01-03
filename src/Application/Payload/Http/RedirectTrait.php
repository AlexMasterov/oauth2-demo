<?php

namespace OAuth2\Application\Payload\Http;

use Equip\Adr\PayloadInterface;
use Equip\Payload;

trait RedirectTrait
{
    protected function redirect($url): PayloadInterface
    {
        return (new Payload)
            ->withSetting('redirect', $url)
            ->withStatus(Payload::STATUS_PERMANENT_REDIRECT);
    }
}
