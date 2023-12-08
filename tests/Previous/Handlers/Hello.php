<?php

namespace Rougin\Slytherin\Previous\Handlers;

class Hello
{
    public function __invoke($request, $response, $next = null)
    {
        $response = $next($request, $response);

        $response->getBody()->write('Hello from middleware');

        return $response;
    }
}
