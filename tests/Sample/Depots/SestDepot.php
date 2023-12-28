<?php declare(strict_types = 1);

namespace Rougin\Slytherin\Sample\Depots;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class SestDepot
{
    /**
     * @var \Rougin\Slytherin\Sample\Depots\TestDepot
     */
    protected $test;

    /**
     * @param \Rougin\Slytherin\Sample\Depots\TestDepot $test
     */
    public function __construct(TestDepot $test)
    {
        $this->test = $test;
    }

    /**
     * @param  string $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function text($data)
    {
        return $this->test->text($data);
    }
}
