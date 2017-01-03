<?php

namespace OAuth2\Application\Payload\Http;

use Equip\Adr\PayloadInterface;
use Equip\Payload;

trait BadRequestTrait
{
    protected function badRequest(array $input, array $messages = []): PayloadInterface
    {
        return (new Payload)
            ->withSetting('template', 'codes/400')
            ->withStatus(Payload::STATUS_BAD_REQUEST)
            ->withInput($input)
            ->withOutput($messages)
            ->withMessages($messages);
    }
}
