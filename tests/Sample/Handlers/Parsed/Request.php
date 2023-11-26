<?php

namespace Rougin\Slytherin\Sample\Handlers\Parsed;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Request implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $data = array('name' => 'Slytherin');

        $request = $request->withParsedBody($data);

        return $delegate->process($request);
    }
}