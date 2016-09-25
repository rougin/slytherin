<?php

namespace Rougin\Slytherin\Http;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Base URI Guesser
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class BaseUriGuesser
{
    /**
     * Guesses the base URI of the application.
     *
     * @param  Psr\Http\Message\ServerRequestInterface $request
     * @return Psr\Http\Message\ServerRequestInterface
     */
    public static function guess(ServerRequestInterface $request)
    {
        $server = $request->getServerParams();

        $basename = basename($server['SCRIPT_FILENAME']);
        $position = strpos($server['SCRIPT_NAME'], $basename) - 1;
        $rootUri  = substr($server['SCRIPT_NAME'], 0, $position);

        $oldPath = $request->getUri()->getPath();
        $newPath = str_replace($rootUri, '', $oldPath);

        $uri = $request->getUri()->withPath($newPath);

        return $request->withUri($uri);
    }
}
