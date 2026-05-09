<?php

namespace Rougin\Slytherin;

use LegacyPHPUnit\TestCase as Legacy;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 *
 * @codeCoverageIgnore
 */
class Testcase extends Legacy
{
    /**
     * @param class-string $exception
     *
     * @return void
     */
    public function doSetExpectedException($exception)
    {
        if (method_exists($this, 'expectException'))
        {
            $this->expectException($exception);

            return;
        }

        $this->setExpectedException($exception);
    }
}
