<?php

namespace Rougin\Slytherin\Http;

use Rougin\Slytherin\Http\Uri;
use Psr\Http\Message\RequestInterface;
use Rougin\Slytherin\Http\MessageTrait;
use Rougin\Slytherin\Http\RequestTrait;

class Request implements RequestInterface
{
    use MessageTrait, RequestTrait;
}
