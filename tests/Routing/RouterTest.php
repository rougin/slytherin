<?php

namespace Rougin\Slytherin\Routing;

/**
 * Router Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class RouterTest extends RouterTestCases
{
    /**
     * Sets up the router.
     *
     * @return void
     */
    public function setUp()
    {
        $this->router = new Router($this->routes);
    }

    /**
     * Tests RouterInterface::routes.
     *
     * @return void
     */
    public function testRoutesMethod()
    {
        $this->exists(get_class($this->router));

        $expected = $this->routes;

        $expected[0][2] = explode('@', $expected[0][2]);

        $expected[0][] = array();

        $result = $this->router->routes();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests RouterInterface::restful.
     *
     * @return void
     */
    public function testRestfulMethod()
    {
        $this->exists(get_class($this->router));

        $expected = array();

        $expected[] = array('GET', '/', array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index'), array());
        $expected[] = array('GET', '/posts', array('PostsController', 'index'), array());
        $expected[] = array('POST', '/posts', array('PostsController', 'store'), array());
        $expected[] = array('DELETE', '/posts/:id', array('PostsController', 'delete'), array());
        $expected[] = array('GET', '/posts/:id', array('PostsController', 'show'), array());
        $expected[] = array('PATCH', '/posts/:id', array('PostsController', 'update'), array());
        $expected[] = array('PUT', '/posts/:id', array('PostsController', 'update'), array());

        $this->router->restful('posts', 'PostsController');

        $result = $this->router->routes();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Router::__construct.
     *
     * @return void
     */
    public function testConstructMagicMethod()
    {
        $expected = array();

        $expected[] = array('GET', '/posts', array('PostsController', 'index'), array());
        $expected[] = array('POST', '/posts', array('PostsController', 'store'), array());

        $router = new Router($expected);

        $result = $router->routes();

        $this->assertEquals($expected, $result);
    }
}
