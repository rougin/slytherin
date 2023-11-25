<?php

namespace Rougin\Slytherin\Forward\Fixture;

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

    protected $query = array();

    protected $parsed = array();

    protected $server = array();

    public function make()
    {
        $config = new Configuration;

        $config->load(__DIR__ . '/Config');

        $config->set('app.http.cookies', $this->cookies);
        $config->set('app.http.files', $this->files);
        $config->set('app.http.get', $this->query);
        $config->set('app.http.post', $this->parsed);
        $config->set('app.http.server', $this->server);

        $container = new Container;

        $app = new Application($container, $config);

        $items = $config->get('app.packages');

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