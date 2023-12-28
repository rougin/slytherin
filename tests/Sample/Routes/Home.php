<?php declare(strict_types = 1);

namespace Rougin\Slytherin\Sample\Routes;

use Rougin\Slytherin\Sample\Depots\EsteDepot;
use Rougin\Slytherin\Sample\Depots\SestDepot;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Home extends Route
{
    /**
     * @var \Rougin\Slytherin\Sample\Depots\SestDepot
     */
    protected $sest;

    /**
     * @param \Rougin\Slytherin\Sample\Depots\SestDepot $sest
     */
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

    /**
     * @param  \Rougin\Slytherin\Sample\Depots\EsteDepot $este
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function param(EsteDepot $este)
    {
        return $este->text('Welcome param!');
    }
}
