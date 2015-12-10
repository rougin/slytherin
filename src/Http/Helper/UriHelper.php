<?php

namespace Rougin\Slytherin\Http\Helper;

class UriHelper
{
    /**
     * Creates a URI string from its various parts.
     *
     * @param  string $scheme
     * @param  string $authority
     * @param  string $path
     * @param  string $query
     * @param  string $fragment
     * @return string
     */
    public static function createUriString(
        $scheme,
        $authority,
        $path,
        $query,
        $fragment
    ) {
        $uri = '';

        if (! empty($scheme)) {
            $uri .= sprintf('%s://', $scheme);
        }

        if (! empty($authority)) {
            $uri .= $authority;
        }

        if ($path) {
            if (empty($path) || '/' !== substr($path, 0, 1)) {
                $path = '/' . $path;
            }

            $uri .= $path;
        }

        if ($query) {
            $uri .= sprintf('?%s', $query);
        }

        if ($fragment) {
            $uri .= sprintf('#%s', $fragment);
        }

        return $uri;
    }
}
