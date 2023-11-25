<?php

namespace Rougin\Slytherin\Forward\Fixture\Routes;

use Rougin\Slytherin\Forward\Fixture\Depots\SestDepot;

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
}