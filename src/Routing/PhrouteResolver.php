<?php

namespace Rougin\Slytherin\Routing;

use Phroute\Phroute\HandlerResolverInterface;

/**
 * Phroute Resolver
 *
 * A handler resolver that wraps the route as the result.
 *
 * https://github.com/mrjgreen/phroute
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class PhrouteResolver implements HandlerResolverInterface
{
    /**
     * @param  \Rougin\Slytherin\Routing\RouteInterface $handler
     * @return array<int, mixed>
     */
    public function resolve($handler)
    {
        return array(new PhrouteWrapper($handler), 'setParams');
    }
}
