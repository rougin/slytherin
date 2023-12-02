<?php

namespace Rougin\Slytherin\Server;

use Psr\Http\Message\ServerRequestInterface;

interface HandlerInterface
{
    public function handle(ServerRequestInterface $request);
}
