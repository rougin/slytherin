<?php

namespace Rougin\Slytherin;

use LegacyPHPUnit\TestCase as Legacy;
use Rougin\Slytherin\Middleware\Interop;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 *
 * @codeCoverageIgnore
 */
class Testcase extends Legacy
{
    /**
     * @return void
     */
    public function checkIfAurynExists()
    {
        if (class_exists('Auryn\Injector'))
        {
            return;
        }

        $text = 'Auryn is not installed.';

        $this->markTestSkipped($text);
    }

    /**
     * @return void
     */
    public function checkIfDiactorosExists()
    {
        if (class_exists('Zend\Diactoros\Response'))
        {
            return;
        }

        $text = 'Zend Diactoros is not installed.';

        $this->markTestSkipped($text);
    }

    /**
     * @return void
     */
    public function checkIfFastRouteExists()
    {
        if (class_exists('FastRoute\RouteCollector'))
        {
            return;
        }

        $text = 'FastRoute is not installed.';

        $this->markTestSkipped($text);
    }

    /**
     * @return void
     */
    public function checkIfInteropExists()
    {
        if (Interop::exists())
        {
            return;
        }

        $text = 'Interop middleware/s not yet installed';

        $this->markTestSkipped($text);
    }

    /**
     * @return void
     */
    public function checkIfLeagueExists()
    {
        if (class_exists('League\Container\Container'))
        {
            return;
        }

        $text = 'League Container is not installed.';

        $this->markTestSkipped($text);
    }

    /**
     * @return void
     */
    public function checkIfPhrouteExists()
    {
        if (class_exists('Phroute\Phroute\RouteCollector'))
        {
            return;
        }

        $text = 'Phroute is not installed.';

        $this->markTestSkipped($text);
    }

    /**
     * @return void
     */
    public function checkIfStratigilityExists()
    {
        if (class_exists('Zend\Stratigility\MiddlewarePipe'))
        {
            return;
        }

        $text = 'Zend Stratigility is not installed.';

        $this->markTestSkipped($text);
    }

    /**
     * @return void
     */
    public function checkIfTwigExists()
    {
        if (class_exists('Twig_Environment'))
        {
            return;
        }

        if (class_exists('Twig\Environment'))
        {
            return;
        }

        $this->markTestSkipped('Twig is not installed.');
    }

    /**
     * @return void
     */
    public function checkIfWhoopsExists()
    {
        if (class_exists('Whoops\Run'))
        {
            return;
        }

        $text = 'Whoops is not installed.';

        $this->markTestSkipped($text);
    }

    /**
     * @param string $needle
     * @param string $haystack
     * @param string $message
     *
     * @return void
     */
    public function doAssertStringContainsString($needle, $haystack, $message = '')
    {
        /** @phpstan-ignore-next-line */
        if (method_exists($this, 'assertStringContainsString'))
        {
            /** @phpstan-ignore-next-line */
            $this->assertStringContainsString($needle, $haystack, $message);

            return;
        }

        /** @phpstan-ignore-next-line */
        $this->assertContains($needle, $haystack, $message);
    }

    /**
     * @param class-string $exception
     *
     * @return void
     */
    public function doSetExpectedException($exception)
    {
        /** @phpstan-ignore-next-line */
        if (method_exists($this, 'expectException'))
        {
            /** @phpstan-ignore-next-line */
            $this->expectException($exception);

            return;
        }

        /** @phpstan-ignore-next-line */
        $this->setExpectedException($exception);
    }
}
