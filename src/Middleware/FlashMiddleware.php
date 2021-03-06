<?php

namespace Neoflow\FlashMessages\Middleware;

use Neoflow\FlashMessages\Exception\FlashException;
use Neoflow\FlashMessages\FlashAwareInterface;
use Neoflow\FlashMessages\FlashAwareTrait;
use Neoflow\FlashMessages\FlashInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class FlashMiddleware implements MiddlewareInterface, FlashAwareInterface
{
    use FlashAwareTrait;

    /**
     * Constructor.
     *
     * @param FlashInterface $flash Flash messages service
     */
    public function __construct(FlashInterface $flash)
    {
        $this->flash = $flash;
    }

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!isset($_SESSION)) {
            throw new FlashException('Load messages from session not possible. Session not started yet.');
        }
        $this->flash->load($_SESSION);

        return $handler->handle($request);
    }
}
