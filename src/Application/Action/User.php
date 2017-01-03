<?php

namespace OAuth2\Application\Action;

use Equip\Adr\PayloadInterface;
use Equip\Contract\ActionInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use OAuth2\Application\Payload\OAuth2\InvalidTokenTrait;
use OAuth2\Application\Payload\RenderTrait;
use OAuth2\Application\Provider\ProviderFactoryInterface;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

final class User implements ActionInterface
{
    use InvalidTokenTrait;
    use RenderTrait;

    /**
     * @var ProviderFactoryInterface
     */
    private $factory;

    public function __construct(ProviderFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function __invoke(array $input): PayloadInterface
    {
        $provider = $input['provider'];

        if (empty($input['token'])) {
            return $this->invalidToken($input);
        }

        try {
            $user = $this->user($input);
        } catch (IdentityProviderException $e) {
            $message = "Token for '{$provider}' provider is invalid";
            return $this->badRequest($input, compact('message'));
        }

        $details = $this->details($owner);

        return $this->render('user', compact('provider', 'details'));
    }

    private function user(array $input): ResourceOwnerInterface
    {
        $provider = $this->factory->create($input['provider']);

        return $provider->getResourceOwner($input['token']);
    }

    private function details(ResourceOwnerInterface $owner): array
    {
        $details = new RecursiveIteratorIterator(
            new RecursiveArrayIterator($owner->toArray())
        );

        return iterator_to_array($details);
    }
}
