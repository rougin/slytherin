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
    /**
     * @var array<string, string>
     */
    protected $cookies = array();

    /**
     * @var array<string, array<string, mixed[]>>
     */
    protected $files = array();

    /**
     * @var \Rougin\Slytherin\Middleware\MiddlewareInterface[]|string[]
     */
    protected $handlers = array();

    /**
     * @var array<string, string>
     */
    protected $query = array();

    /**
     * @var \Rougin\Slytherin\Integration\IntegrationInterface[]|string[]
     */
    protected $packages = array();

    /**
     * @var array<string, string>
     */
    protected $parsed = array();

    /**
     * @var array<string, string>
     */
    protected $server = array();

    /**
     * @param  string $name
     * @param  string $file
     * @return self
     */
    public function addFile($name, $file)
    {
        if (! isset($this->files[$name]))
        {
            $item = array('error' => array());
            $item['name'] = array();
            $item['size'] = array();
            $item['tmp_name'] = array();
            $item['type'] = array();

            $this->files[$name] = $item;
        }

        $item = $this->files[$name];

        $item['error'][] = 0;
        $item['name'][] = basename($file);
        $item['size'][] = filesize($file);
        $item['tmp_name'][] = $file;
        $item['type'][] = mime_content_type($file);

        $this->files[$name] = $item;

        return $this;
    }

    /**
     * @param  \Rougin\Slytherin\Middleware\MiddlewareInterface|string $handler
     * @return self
     */
    public function addHandler($handler)
    {
        $this->handlers[] = $handler;

        return $this;
    }

    /**
     * @param  \Rougin\Slytherin\Integration\IntegrationInterface|string $package
     * @return self
     */
    public function addPackage($package)
    {
        $this->packages[] = $package;

        return $this;
    }

    /**
     * @return \Rougin\Slytherin\System
     */
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
        /** @var \Rougin\Slytherin\Middleware\MiddlewareInterface[]|string[] */
        $items = $config->get('app.middlewares');

        $items = array_merge($items, $this->handlers);

        $config->set('app.middlewares', $items);
        // -------------------------------------------

        $container = new Container;

        $app = new Application($container, $config);

        // Set the custom packages -------------------
        /** @var \Rougin\Slytherin\Integration\IntegrationInterface[]|string[] */
        $items = $config->get('app.packages');

        $items = array_merge($items, $this->packages);
        // -------------------------------------------

        return $app->integrate($items);
    }

    /**
     * @param  array<string, string> $cookies
     * @return self
     */
    public function setCookies($cookies)
    {
        $this->cookies = $cookies;

        return $this;
    }

    /**
     * @param  array<string, array<string, mixed[]>> $files
     * @return self
     */
    public function setFiles($files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * @param  array<string, string> $query
     * @return self
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @param  array<string, string> $parsed
     * @return self
     */
    public function setParsed($parsed)
    {
        $this->parsed = $parsed;

        return $this;
    }

    /**
     * @param  array<string, string> $server
     * @return self
     */
    public function setServer($server)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * @param  string $method
     * @param  string $uri
     * @return self
     */
    public function setUrl($method, $uri)
    {
        $this->server['REQUEST_METHOD'] = $method;

        $this->server['REQUEST_URI'] = $uri;

        $this->server['SERVER_NAME'] = 'localhost';

        $this->server['SERVER_PORT'] = '8000';

        return $this;
    }
}
