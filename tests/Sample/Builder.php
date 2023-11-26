<?php

namespace Rougin\Slytherin\Sample;

use Rougin\Slytherin\Application;
use Rougin\Slytherin\Configuration;
use Rougin\Slytherin\Container\Container;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Builder
{
    protected $cookies = array();

    protected $files = array();

    protected $handlers = array();

    protected $query = array();

    protected $packages = array();

    protected $parsed = array();

    protected $server = array();

    public function addHandler($handler)
    {
        $this->handlers[] = $handler;

        return $this;
    }

    public function addPackage($package)
    {
        $this->packages[] = $package;

        return $this;
    }

    public function make()
    {
        $config = new Configuration;

        $config->load(__DIR__ . '/Config');

        // Set the server request globals -----------------
        $config->set('app.http.cookies', $this->cookies);

        $config->set('app.http.files', $this->files);

        $config->set('app.http.get', (array) $this->query);

        $config->set('app.http.post', $this->parsed);

        $config->set('app.http.server', $this->server);
        // ------------------------------------------------

        // Set the custom middlewares ----------------
        $items = $config->get('app.middlewares');

        $items = array_merge($items, $this->handlers);

        $config->set('app.middlewares', $items);
        // -------------------------------------------

        $container = new Container;

        $app = new Application($container, $config);

        // Set the custom packages -------------------
        $items = $config->get('app.packages');

        $items = array_merge($items, $this->packages);
        // -------------------------------------------

        return $app->integrate($items);
    }

    public function setCookies($cookies)
    {
        $this->cookies = $cookies;

        return $this;
    }

    public function setFiles($files)
    {
        $this->files = $files;

        return $this;
    }

    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    public function setParsed($parsed)
    {
        $this->parsed = $parsed;

        return $this;
    }

    public function setServer($server)
    {
        $this->server = $server;

        return $this;
    }

    public function setUrl($method, $uri)
    {
        $this->server['REQUEST_METHOD'] = $method;

        $this->server['REQUEST_URI'] = $uri;

        $this->server['SERVER_NAME'] = 'localhost';

        $this->server['SERVER_PORT'] = '8000';

        return $this;
    }
}