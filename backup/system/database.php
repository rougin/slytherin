<?php if ( ! defined('SYSTEM')) exit('No direct script access allowed');

/**
 * Slytherin - Yet another barebones web framework for PHP
 *
 * @author     Rougin Royce Gutib <rougin.royce@gmail.com>
 * @package    Slytherin
 */

/**
 * Customized PHP Data Ojects (PDO) Active Record Class
 */
class Database
{

	private static $count = NULL;
	private static $delete = NULL;
	private static $distinct = NULL;
	private static $from = NULL;
	private static $group_by = NULL;
	private static $having = NULL;
	private static $insert = NULL;
	private static $join = NULL;
	private static $like = NULL;
	private static $limit = NULL;
	private static $order_by = NULL;
	private static $select = NULL;
	private static $update = NULL;
	private static $where = NULL;
	private static $where_in = NULL;
	
	private static $result = NULL;
	private static $_instance = NULL; /* load a null instance */

	private static $query = NULL;

	public function _having($fields, $data = NULL, $operator)
	{
		if (isset($fields) && isset($data)) {
			self::$having .= self::check_syntax(self::$having, $operator, 'having');
			self::$having .= $fields . self::check_symbol($fields) . " '" . $data . "' ";
		} else if (is_array($fields)) {
			$counter = 0;

			self::$having .= self::check_syntax(self::$having, $operator, 'having');
			foreach ($fields as $key => $value) {
				self::$having .= $key . self::check_symbol($key) . " '" . $value . "' ";

				if ($counter++ < count($fields) - 1) {
					self::$having .= ", ";
				}
			}
		} else {
			self::$having .= self::check_syntax(self::$where, $operator, 'where');
			self::$having .= "HAVING " . $fields . " ";
		}

		return self::get_instance();
	}

	public function _like($field, $match, $wildcard = 'both', $boolean, $operator)
	{
		if (self::$where != NULL) {
			$syntax = self::$where;
		} else if (self::$where_in != NULL) {
			$syntax = self::$where_in;	
		} else {
			$syntax = self::$like;
		}

		self::$like .= self::check_syntax($syntax, $operator, 'where');
		self::$like .= $field . " " . $boolean . "LIKE '" . self::get_wildcard($match, $wildcard) . "' ";

		return self::get_instance();
	}

	public function _select($fields, $operator = NULL)
	{
		if ($operator != NULL) {
			self::$select = "SELECT " . $operator . "(" . $fields . ") ";
		} else {
			self::$select = "SELECT " . $fields . " ";
		}

		return self::get_instance();
	}

	public function _where($field, $data = NULL, $operator = NULL)
	{
		if (is_array($field)) {
			foreach ($field as $key => $value) {
				self::$where .= self::check_syntax(self::$where, $operator, 'where');
				self::$where .= $key . self::check_symbol($key) . " '" . $value . "' ";
			}
		} else if ($field != NULL && $data != NULL) {
			self::$where .= self::check_syntax(self::$where, $operator, 'where');
			self::$where .= $field . self::check_symbol($field) . " '" . $data . "' ";
		} else {
			self::$where .= self::check_syntax(self::$where, $operator, 'where');
			self::$where .= $field . " ";
		}

		return self::get_instance();
	}

	public function _where_in($field, $data, $boolean = NULL, $operator = NULL)
	{
		/* WHERE $field in ($data) */
		if (is_array($data)) {
			$counter = 0;
			$datas = NULL;

			foreach ($data as $each_data) {
				$datas .= "'" . $each_data . "'";

				if ($counter++ < count($data) - 1) {
					$datas .= ", ";
				}
			}
		}

		self::$where_in .= self::check_syntax(self::$where_in, $operator, 'where');
		self::$where_in .= $field . " " . $boolean . "IN (" . $datas . ") ";

		return self::get_instance();
	}

	public function check_symbol($field)
	{
		if (strpos($field, '!=') !== FALSE || strpos($field, '<') !== FALSE || strpos($field, '>') !== FALSE || strpos($field, '=') !== FALSE) {
			return "";
		} else {
			return " =";
		}
	}

	public function check_syntax($syntax, $operator, $type)
	{
		if (preg_match("/$type/i", $syntax)) {
			return strtoupper($operator) . " ";
		} else {
			return strtoupper($type) . " ";
		}
	}

	public function count_all($table)
	{
		self::$count = 'SELECT COUNT(*) as rows ';
		return self::get($table)->row_object()->rows;
	}

	public function delete($table, $data = NULL)
	{
		self::$delete .= "DELETE FROM " . $table . " ";

		if ($data != NULL) {
			self::where($data);
		}

		return $this->get($table);
	}

	public function distinct()
	{
		self::$distinct = "DISTINCT ";

		return self::get_instance();
	}

	public function from($tables)
	{
		self::$from = "FROM " . $tables . " ";

		return self::get_instance();
	}

	public function get($table = NULL)
	{
		include(APPPATH . 'config/database.php');

		$query = NULL;

		if (self::$insert != NULL) {
			$query .= self::$insert;
		} else if (self::$delete != NULL) {
			$query .= self::$delete;
		} else if (self::$update != NULL) {
			$query .= self::$update;
		} else {
			if (self::$select != NULL) {
				$query .= self::$select;
			} else if (self::$count != NULL) {
				$query .= self::$count;
			} else {
				$query .= "SELECT * ";
			}

			if (self::$from != NULL) {
				$query .= self::$from;
			} else if ($table != NULL) {
				$query .= "FROM " . $table . " ";
			}
		}

		if (self::$join != NULL) {
			$query .= self::$join;
		}

		if (self::$where != NULL) {
			$query .= self::$where;
		} else if (self::$where_in != NULL) {
			$query .= self::$where_in;
		}

		if (self::$having != NULL) {
			$query .= self::$having;
		}

		if (self::$group_by != NULL) {
			$query .= self::$group_by;
		}

		if (self::$distinct != NULL) {
			$query = str_replace("SELECT ", "SELECT DISTINCT ", $query);
		}

		if (self::$like != NULL) {
			$query .= self::$like;	
		}

		if (self::$order_by != NULL) {
			$query .= self::$order_by;	
		}

		if (self::$limit != NULL) {
			$query .= self::$limit;	
		}

		try {
			self::$query = NULL;
			self::$query = $query;

			$database = new PDO($db['default']['dbdriver'] . ':host=' . $db['default']['hostname'] . ';dbname=' . $db['default']['database'],
				$db['default']['username'], $db['default']['password']);

			$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
			self::$result = $database->prepare(self::$query);
			self::$result->execute();

			/* set all statements to null */
			self::$count = NULL;
			self::$delete = NULL;
			self::$distinct = NULL;
			self::$from = NULL;
			self::$group_by = NULL;
			self::$having = NULL;
			self::$insert = NULL;
			self::$join = NULL;
			self::$like = NULL;
			self::$limit = NULL;
			self::$order_by = NULL;
			self::$select = NULL;
			self::$update = NULL;
			self::$where = NULL;
			self::$where_in = NULL;

		} catch (PDOException $e) {
			self::$join = '';
			exit('Database connection could not be established.');
		}

		return self::get_instance();
	}

	public function get_instance()
	{
		/* if there is not instance, create a new instance */
		if (self::$_instance === NULL) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}

	public function get_where($table, $data)
	{
		self::where($data);
		self::get($table);

		return self::get_instance();
	}

	public function get_wildcard($match, $wildcard)
	{
		if ($wildcard == 'both') {
			return "%" . $match . "%";
		} else if ($wildcard == 'left') {
			return "%" . $match;
		} else if ($wildcard == 'right') {
			return $match . "%";
		}
	}

	public function group_by($fields)
	{
		self::$group_by .= "GROUP BY ";

		if (is_array($fields)) {
			$counter = 0;

			foreach ($fields as $field) {
				self::$group_by .= $field;

				if ($counter++ < count($fields) - 1) {
					self::$group_by .= ", ";
				}
			}
		}

		return self::get_instance();
	}

	public function having($fields, $data = NULL)
	{
		return self::_having($fields, $data, 'AND');
	}

	public function insert($table, $data)
	{
		foreach ($data as $key => $value) {
			$fields[] = $key;
			$values[] = "'" . $value . "'";
		}

		$fields = implode(', ', $fields);
		$values = implode(', ', $values);

		self::$insert = "INSERT INTO " . $table . " (" . $fields . ") VALUES (" . $values . ")";

		return $this->get($table);
	}

	public function join($table, $field, $type = NULL)
	{
		if ($type != NULL) {
			$type = strtoupper($type) . " ";
		}

		self::$join .= $type . "JOIN " . $table . " ON " . $field . ' ';

		return self::get_instance();
	}

	public function like($field, $match, $wildcard = 'both')
	{
		return self::_like($field, $match, $wildcard, '', 'AND');
	}

	public function limit($result, $offset = NULL)
	{
		if ($offset != NULL) {
			$offset = $offset . ", ";
		}

		self::$limit .= "LIMIT " . $offset . $result; 

		return self::get_instance();
	}

	public function not_like($field, $match, $wildcard = 'both')
	{
		return self::_like($field, $match, $wildcard, 'NOT ', 'AND');
	}

	public function or_having($fields, $data = NULL)
	{
		return self::_having($fields, $data, 'OR');
	}

	public function or_like($field, $match, $wildcard = 'both')
	{
		return self::_like($field, $match, $wildcard, '', 'OR');
	}

	public function or_not_like($field, $match, $wildcard = 'both')
	{
		return self::_like($field, $match, $wildcard, 'NOT ', 'OR');
	}

	public function or_where($field, $data = NULL)
	{
		return self::_where($field, $data, 'OR');
	}

	public function or_where_in($field, $data)
	{
		return self::_where_in($field, $data, '', 'OR');
	}

	public function or_where_not_in($field, $data)
	{
		return self::_where_in($field, $data, 'NOT ', 'OR');
	}

	public function order_by($fields, $arrangement = NULL)
	{
		$space = NULL;

		self::$order_by .= self::check_syntax(self::$order_by, ',', 'order by');

		if ($arrangement == NULL) {
			$space = " ";
		} else {
			$arrangement = " " . $arrangement;
		}

		self::$order_by .= $fields . $space . strtoupper($arrangement) . " ";

		return self::get_instance();
	}

	public function result()
	{
		$data = self::$result->fetchAll(PDO::FETCH_OBJ);
		self::$result->closeCursor();
		return $data;
	}

	public function result_array()
	{
		$data = self::$result->fetchAll(PDO::FETCH_ASSOC);
		self::$result->closeCursor();
		return $data;
	}

	public function row_array()
	{
		$data = self::$result->fetch(PDO::FETCH_ASSOC);
		self::$result->closeCursor();
		return $data;
	}

	public function row_object()
	{
		$data = self::$result->fetch(PDO::FETCH_OBJ);
		self::$result->closeCursor();
		return $data;
	}

	public function select($fields)
	{
		return self::_select($fields, NULL);
	}

	public function select_avg($fields)
	{
		return self::_select($fields, 'AVG');
	}

	public function select_max($fields)
	{
		return self::_select($fields, 'MAX');
	}

	public function select_min($fields)
	{
		return self::_select($fields, 'MIN');
	}

	public function select_sum($fields)
	{
		return self::_select($fields, 'SUM');
	}

	public function show_query()
	{
		return self::$query . '<br/><br/>';
	}

	public function update($table, $data)
	{
		foreach ($data as $key => $value) {
			$fields[] = $key . " = '" . $value . "'";
		}

		$fields = implode(', ', $fields);

		self::$update = "UPDATE " . $table . " SET " . $fields . " ";

		$this->get();
	}

	public function where($field, $data = NULL)
	{
		return self::_where($field, $data, 'AND');
	}

	public function where_in($field, $data)
	{
		return self::_where_in($field, $data, '', 'AND');
	}

	public function where_not_in($field, $data)
	{
		return self::_where_in($field, $data, 'NOT ', 'AND');
	}

}