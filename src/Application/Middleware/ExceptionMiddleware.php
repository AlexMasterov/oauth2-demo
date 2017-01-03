<?php

namespace OAuth2\Application\Middleware;

use Equip\Adr\PayloadInterface;
use Equip\Payload;
use Equip\Resolver\ResolverTrait;
use Equip\Responder\FormattedResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Relay\ResolverInterface;
use Throwable;

class ExceptionMiddleware
{
    use ResolverTrait;

    const MINIMUM_HTTP_CODE = 100;
    const MAXIMUM_HTTP_CODE = 599;
    const MISSING_HTTP_CODE = 200;

    /**
     * @var integer
     */
    private $missingHttpCode;

    public function __construct(
        ResolverInterface $resolver,
        $missingHttpCode = self::MISSING_HTTP_CODE
    ) {
        $this->resolver = $resolver;
        $this->missingHttpCode = $missingHttpCode;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface
    {
        try {
            return $next($request, $response);
        } catch (Throwable $throwable) {
            return $this->withThrowable($request, $response, $throwable);
        }
    }

    public function withThrowable(
        ServerRequestInterface $request,
        ResponseInterface $response,
        Throwable $throwable
    ): ResponseInterface
    {
        $response = $this->status($response, $throwable);
        $response = $this->format($request, $response, $throwable);

        return $response;
    }

    private function status(
        ResponseInterface $response,
        Throwable $throwable
    ): ResponseInterface
    {
        $throwableCode = $throwable->getCode();

        $options = [
            'default'   => $this->missingHttpCode,
            'min_range' => self::MINIMUM_HTTP_CODE,
            'max_range' => self::MAXIMUM_HTTP_CODE,
        ];

        $code = filter_var($throwableCode, FILTER_VALIDATE_INT, compact('options'));

        return $response->withStatus($code);
    }

    private function format(
        ServerRequestInterface $request,
        ResponseInterface $response,
        Throwable $throwable
    ): ResponseInterface
    {
        $formatter = $this->resolve(FormattedResponder::class);
        $payload = $this->payload($throwable);

        return $formatter($request, $response, $payload);
    }

    private function payload(Throwable $throwable): PayloadInterface
    {
        $message = $throwable->getMessage();

        $payload = $this->resolve(Payload::class);
        $payload = $payload
            ->withSetting('template', 'throwable')
            ->withOutput(compact('message'));

        return $payload;
    }
}
