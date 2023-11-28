<?php

namespace Rougin\Slytherin;

use LegacyPHPUnit\TestCase as Legacy;

class Testcase extends Legacy
{
    public function setExpectedException($exception)
    {
        if (method_exists($this, 'expectException'))
        {
            $this->expectException($exception);
        }
        else
        {
            parent::setExpectedException($exception);
        }
    }
}