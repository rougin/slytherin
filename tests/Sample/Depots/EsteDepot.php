<?php declare(strict_types = 1);

namespace Rougin\Slytherin\Sample\Depots;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class EsteDepot
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
     * @param  string $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function text($data)
    {
        return $this->sest->text($data);
    }
}
