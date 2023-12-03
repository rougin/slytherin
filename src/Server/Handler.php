<?php

namespace Rougin\Slytherin\Server;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Handler implements HandlerInterface
{
    /**
     * @var \Rougin\Slytherin\Server\HandlerInterface
     */
    protected $default;

    /**
     * @var integer
     */
    protected $index = 0;

    /**
     * @var \Rougin\Slytherin\Server\MiddlewareInterface[]
     */
    protected $stack;

    /**
     * @param \Rougin\Slytherin\Server\MiddlewareInterface[] $stack
     * @param \Rougin\Slytherin\Server\HandlerInterface      $default
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
     * @return \Rougin\Slytherin\Server\HandlerInterface
     */
    protected function next()
    {
        $next = clone $this;

        $next->index++;

        return $next;
    }
}
