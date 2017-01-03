<?php

namespace OAuth2\Application\Action;

use Equip\Adr\PayloadInterface;
use Equip\Contract\ActionInterface;
use Equip\SessionInterface;
use OAuth2\Application\Payload\RedirectTrait;

final class Logout implements ActionInterface
{
    use RedirectTrait;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function __invoke(array $input): PayloadInterface
    {
        $this->storeToken($input);

        return $this->redirect('/');
    }

    private function storeToken(array $input)
    {
        $providerName = $input['provider'];

        $newToken = [
            $providerName => null,
        ];

        $tokens = array_replace($this->session->get('tokens', []), $newToken);

        $this->session->set('tokens', $tokens);
    }
}
