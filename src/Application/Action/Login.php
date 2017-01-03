<?php

namespace OAuth2\Application\Action;

use Equip\Adr\PayloadInterface;
use Equip\Contract\ActionInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use OAuth2\Application\Payload\Http\BadRequestTrait;
use OAuth2\Application\Payload\Http\RedirectTrait;
use OAuth2\Application\Provider\ProviderFactoryInterface;

final class Login implements ActionInterface
{
    use BadRequestTrait;
    use RedirectTrait;

    /**
     * @var ProviderFactoryInterface
     */
    private $factory;

    public function __construct(
        ProviderFactoryInterface $factory
    ) {
        $this->factory = $factory;
    }

    public function __invoke(array $input): PayloadInterface
    {
        if (!empty($input['error'])) {
            return $this->badRequest($input);
        }

        try {
            $token = $this->token($input);
        } catch (IdentityProviderException $e) {
            $message = $e->getMessage();
            return $this->badRequest($input, compact('message'));
        }

        $userUrl = $this->userUrl($input);

        return $this->redirect($userUrl);
    }

    private function token(array $input): AccessToken
    {
        $code = $input['code'];

        $provider = $this->factory->create($input['provider']);

        return $provider->getAccessToken('authorization_code', compact('code'));
    }

    private function userUrl(array $input): string
    {
        $provider = $input['provider'];
        // no named route; hardcode the url here
        return "/user/{$provider}";
    }
}
