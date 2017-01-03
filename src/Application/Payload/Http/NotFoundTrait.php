<?php

namespace OAuth2\Application\Payload\Http;

use Equip\Adr\PayloadInterface;
use Equip\Payload;

trait NotFoundTrait
{
    protected function notFound(array $input, array $messages = []): PayloadInterface
    {
        return (new Payload)
            ->withSetting('template', 'codes/404')
            ->withStatus(Payload::STATUS_NOT_FOUND)
            ->withInput($input)
            ->withOutput($messages);
    }
}
