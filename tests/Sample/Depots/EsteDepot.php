<?php

namespace Rougin\Slytherin\Sample\Depots;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class EsteDepot
{
    protected $sest;

    public function __construct(SestDepot $sest)
    {
        $this->sest = $sest;
    }

    public function text($data)
    {
        return $this->sest->text($data);
    }
}
