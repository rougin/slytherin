<?php declare(strict_types = 1);

namespace Rougin\Slytherin\Routing;

use Phroute\Phroute\HandlerResolverInterface;

/**
 * Phroute Resolver
 *
 * A handler resolver that wraps the route as the result.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 *
 * @link https://github.com/mrjgreen/phroute
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
