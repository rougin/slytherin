<?php

namespace Rougin\Slytherin\Fixture\Components;

use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Http\Response;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class InvalidHttpRequest extends HttpComponent
{
    /**
     * @return mixed
     */
    public function get()
    {
        return array(new NewClass, new Response);
    }
}
