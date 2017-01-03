<?php

namespace OAuth2\Application\Payload\OAuth2;

use Equip\Adr\PayloadInterface;
use Equip\Payload;

trait tokenNotFoundTrait
{
    protected function invalidToken(array $input): PayloadInterface
    {
        $message = 'No token found for this provider';

        return (new Payload)
            ->withSetting('template', 'codes/400')
            ->withStatus(Payload::STATUS_BAD_REQUEST)
            ->withOutput(compact('message'));
    }
}
