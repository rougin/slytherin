<?php

namespace Rougin\Slytherin;

use LegacyPHPUnit\TestCase as Legacy;

class Testcase extends Legacy
{
    public function setExpectedException($exception)
    {
        /** @var callable */
        $class = array($this, 'setExpectedException');

        if (method_exists($this, 'expectException'))
        {
            /** @var callable */
            $class = array($this, 'expectException');
        }

        call_user_func($class, $exception);
    }
}