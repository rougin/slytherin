<?php

namespace Rougin\Slytherin\Fixture\Classes;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class WithScalarTypes
{
    /**
     * @param boolean $enabled
     *
     * @return string
     */
    public function enabled(bool $enabled)
    {
        return gettype($enabled);
    }

    /**
     * @param float $amount
     *
     * @return string
     */
    public function rate(float $amount)
    {
        return gettype($amount);
    }

    /**
     * @param string $query
     *
     * @return string
     */
    public function search(string $query)
    {
        return gettype($query);
    }

    /**
     * @param integer $id
     *
     * @return string
     */
    public function show(int $id)
    {
        return gettype($id);
    }
}
