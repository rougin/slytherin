<?php

namespace Rougin\Slytherin\IoC;

use ReflectionClass;
use League\Container\Container as BaseLeagueContainer;

use Rougin\Slytherin\IoC\Exception\NotFoundException;

/**
 * LeagueContainer
 *
 * A simple implementation of a container that is based on League\Container.
 *
 * http://container.thephpleague.com
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class LeagueContainer extends BaseLeagueContainer implements ContainerInterface {}
