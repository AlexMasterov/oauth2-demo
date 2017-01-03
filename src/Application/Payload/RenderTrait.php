<?php

namespace OAuth2\Application\Payload;

use Equip\Adr\PayloadInterface;
use Equip\Payload;

trait RenderTrait
{
    protected function render($template, array $output = []): PayloadInterface
    {
        return (new Payload)
            ->withSetting('template', $template)
            ->withStatus(Payload::STATUS_OK)
            ->withOutput($output);
    }
}
