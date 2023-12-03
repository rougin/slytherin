<?php

namespace Rougin\Slytherin\Sample\Routes;

use Rougin\Slytherin\Sample\Depots\EsteDepot;
use Rougin\Slytherin\Sample\Depots\SestDepot;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Home extends Route
{
    protected $sest;

    public function __construct(SestDepot $sest)
    {
        $this->sest = $sest;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index()
    {
        return $this->sest->text('Welcome home!');
    }

    public function param(EsteDepot $este)
    {
        return $este->text('Welcome param!');
    }
}
