<?php if ( ! defined('SYSTEM')) exit('No direct script access allowed');

/**
 * Slytherin - Yet another barebones web framework for PHP
 *
 * @author     Rougin Royce Gutib <rougin.royce@gmail.com>
 * @package    Slytherin
 */

/**
 * Product Model Class
 */
class Product extends Model
{

	public static $table = 'product';
	public static $primary_key = 'product_id';
}