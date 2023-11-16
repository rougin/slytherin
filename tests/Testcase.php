<?php

namespace Rougin\Slytherin;

use LegacyPHPUnit\TestCase as PHPUnit;

class Testcase extends PHPUnit
{
	public function setExpectedException($exception)
	{
		$this->expectException($exception);
	}
}