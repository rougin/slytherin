<?php

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 * @codeCoverageIgnore
 */
class Handler implements HandlerInterface
{
    /**
     * @var \Rougin\Slytherin\Middleware\HandlerInterface
     */
    protected $default;

    /**
     * @var integer
     */
    protected $index = 0;

    /**
     * @var \Rougin\Slytherin\Middleware\MiddlewareInterface[]
     */
    protected $stack;

    /**
     * @param \Rougin\Slytherin\Middleware\MiddlewareInterface[] $stack
     * @param \Rougin\Slytherin\Middleware\HandlerInterface      $default
     */
    public function __construct(array $stack, HandlerInterface $default)
    {
        $this->default = $default;

        $this->stack = $stack;
    }

    /**
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request)
    {
        return $this->handle($request);
    }

    /**
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request)
    {
        if (! isset($this->stack[$this->index]))
        {
            return $this->default->handle($request);
        }

        $item = $this->stack[(int) $this->index];

        $next = $this->next();

        return $item->process($request, $next);
    }

    /**
     * @return \Rougin\Slytherin\Middleware\HandlerInterface
     */
    protected function next()
    {
        $next = clone $this;

        $next->index++;

        return $next;
    }
}
