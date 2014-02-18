<?php

class Database
{

	public static $select = NULL;
	public static $from = NULL;
	public static $join = NULL;
	public static $where = NULL;
	public static $where_in = NULL;
	public static $like = NULL;
	public static $group_by = NULL;
	public static $distinct = NULL;
	public static $having = NULL;
	public static $order_by = NULL;
	public static $limit = NULL;
	public static $insert = NULL;
	public static $update = NULL;
	public static $delete = NULL;
	
	public static $results = NULL;
	private static $_instance = NULL; /* load a null instance */

	public function __construct()
	{
		/* connect to the database using the specified information in the configuration */
		mysql_connect($GLOBALS['database']['hostname'], $GLOBALS['database']['username'], $GLOBALS['database']['password']);
		/* selects the database */
		mysql_select_db($GLOBALS['database']['database_name']);
	}

	public function _having($fields, $data = NULL, $operator)
	{
		if (isset($fields) && isset($data))
		{
			self::$having .= self::check_syntax(self::$having, $operator, 'having');
			self::$having .= $fields . self::check_symbol($fields) . " '" . $data . "' ";
		}
		else if (is_array($fields))
		{
			$counter = 0;
			self::$having .= self::check_syntax(self::$having, $operator, 'having');
			foreach ($fields as $key => $value)
			{
				self::$having .= $key . self::check_symbol($key) . " '" . $value . "' ";
				if ($counter++ < count($fields) - 1) { self::$having .= ", "; }
			}	
		}
		else
		{
			self::$having .= self::check_syntax(self::$where, $operator, 'where');
			self::$having .= "HAVING " . $fields . " ";
		}
		return self::get_instance();
	}

	public function _like($field, $match, $wildcard = 'both', $boolean, $operator)
	{
		if (self::$where != NULL)
		{
			$syntax = self::$where;
		}
		else if (self::$where_in != NULL)
		{
			$syntax = self::$where_in;	
		}
		else
		{
			$syntax = self::$like;
		}
		self::$like .= self::check_syntax($syntax, $operator, 'where');
		self::$like .= $field . " " . $boolean . "LIKE " . self::get_wildcard($match, $wildcard) . " ";
		return self::get_instance();
	}

	public function _select($fields, $operator = NULL)
	{
		if ($operator != NULL)
		{
			self::$select = "SELECT " . $operator . "(" . $fields . ") ";
		}
		else
		{
			self::$select = "SELECT " . $fields . " ";
		}
		return self::get_instance();
	}

	public function _where($field, $data = NULL, $operator = NULL)
	{
		if (is_array($field))
		{
			foreach ($field as $key => $value)
			{
				self::$where .= self::check_syntax(self::$where, $operator, 'where');
				self::$where .= $key . self::check_symbol($key) . " '" . $value . "' ";
			}
		}
		else if ($field != NULL && $data != NULL)
		{
			self::$where .= self::check_syntax(self::$where, $operator, 'where');
			self::$where .= $field . self::check_symbol($field) . " '" . $data . "' ";
		}
		else
		{
			self::$where .= self::check_syntax(self::$where, $operator, 'where');
			self::$where .= $field . " ";
		}
		return self::get_instance();
	}

	public function _where_in($field, $data, $boolean = NULL, $operator = NULL)
	{
		/* WHERE $field in ($data) */
		if (is_array($data))
		{
			$counter = 0;
			$datas = NULL;
			foreach ($data as $each_data)
			{
				$datas .= "'" . $each_data . "'";
				if ($counter++ < count($data) - 1) { $datas .= ", "; }
			}
		}
		self::$where_in .= self::check_syntax(self::$where_in, $operator, 'where');
		self::$where_in .= $field . " " . $boolean . "IN (" . $datas . ") ";
		return self::get_instance();
	}

	public function check_symbol($field)
	{
		if (strpos($field, '!=') !== FALSE || strpos($field, '<') !== FALSE || strpos($field, '>') !== FALSE || strpos($field, '=') !== FALSE)
		{
			return "";
		}
		else
		{
			return " =";
		}
	}

	public function check_syntax($syntax, $operator, $type)
	{
		if (preg_match("/$type/i", $syntax))
		{
			return strtoupper($operator) . " ";
		}
		else
		{
			return strtoupper($type) . " ";
		}
	}

	public function delete($table, $data = NULL)
	{
		if ($data != NULL)
		{
			self::where($data);
		}
		self::$delete = "DELETE FROM " . $table . " ";
		return self::$delete . self::$where;
	}

	public function distinct()
	{
		self::$distinct = "DISTINCT ";
		return self::get_instance();
	}

	public function execute($query)
	{
		self::$results = mysql_query($query);
		if (!$query)
		{
			/* prompt a mysql_error */
			Error::mysql_error(mysql_error());
		}
	}

	public function from($tables)
	{
		self::$from = "FROM " . $tables . " ";
		return self::get_instance();
	}

	public function get($table = NULL)
	{
		$query = NULL;
		if (self::$select != NULL)
		{
			$query .= self::$select;
		}
		else
		{
			$query .= "SELECT * ";
		}
		if (self::$from != NULL)
		{
			$query .= self::$from;
		}
		else if ($table != NULL)
		{
			$query .= "FROM " . $table . " ";
		}
		if (self::$where != NULL)
		{
			$query .= self::$where;
		}
		else if (self::$where_in != NULL)
		{
			$query .= self::$where_in;
		}
		if (self::$having != NULL)
		{
			$query .= self::$having;
		}
		if (self::$group_by != NULL)
		{
			$query .= self::$group_by;
		}
		if (self::$distinct != NULL)
		{
			$query = str_replace("SELECT ", "SELECT DISTINCT ", $query);
		}
		if (self::$like != NULL)
		{
			$query .= self::$like;	
		}
		if (self::$join != NULL)
		{
			$query .= self::$join;
		}
		if (self::$order_by != NULL)
		{
			$query .= self::$order_by;	
		}
		if (self::$limit != NULL)
		{
			$query .= self::$limit;	
		}

		self::execute($query);
		return self::get_instance();
	}

	public function get_instance()
	{
		/* if there is not instance, create a new instance */
		if (self::$_instance === NULL) { self::$_instance = new self; }
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
		if ($wildcard == 'both')
		{
			return "%" . $match . "%";
		}
		else if ($wildcard == 'left')
		{
			return "%" . $match;
		}
		else if ($wildcard == 'right')
		{
			return $match . "%";
		}
	}

	public function group_by($fields)
	{
		self::$group_by .= "GROUP BY ";
		if (is_array($fields))
		{
			$counter = 0;
			foreach ($fields as $field)
			{
				self::$group_by .= $field;
				if ($counter++ < count($fields) - 1) { self::$group_by .= ", "; }
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
		foreach ($data as $key => $value)
		{
			$fields[] = $key;
			$values[] = "'" . $value . "'";
		}
		$fields = implode(', ', $fields);
		$values = implode(', ', $values);
		self::$insert = "INSERT INTO " . $table . " (" . $fields . ") VALUES (" . $values . ")";
		return self::$insert;
	}

	public function into($result)
	{
		/* if the result wants to be as an object */
		if ($result == 'object')
		{
			/* fetch data by object */
			while($row_data = mysql_fetch_object(self::$results))
			{
				/* assign the new row to the results */
				$results[] = $row_data;
			}
		}
		if ($result == 'object_row')
		{
			/* assign the new row to the results */
			$results = mysql_fetch_object(self::$results);
		}
		/* else if the result wants to be as an array */
		else if ($result == 'array')
		{
			/* fetch data by array */
			while($row_data = mysql_fetch_assoc(self::$results))
			{
				/* assign the new row to the results */
				$results[] = $row_data;
			}
		}
		return $results;
	}

	public function join($table, $field, $type = NULL)
	{
		if ($type != NULL) { $type = strtoupper($type) . " "; }
		self::$join =  $type . "JOIN " . $table . " ON " . $field;
		return self::get_instance();
	}

	public function like($field, $match, $wildcard = 'both')
	{
		return self::_like($field, $match, $wildcard, '', 'AND');
	}

	public function limit($result, $offset = NULL)
	{
		if ($offset != NULL)
		{
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
		if ($arrangement == NULL)
		{
			$space = " ";
		}
		else
		{
			$arrangement = " " . $arrangement;
		}
		self::$order_by .= $fields . $space . strtoupper($arrangement) . " ";
		return self::get_instance();
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

	public function update($table, $data)
	{
		foreach ($data as $key => $value)
		{
			$fields[] = $key . " = '" . $value . "'";
		}
		$fields = implode(', ', $fields);
		self::$update = "UPDATE " . $table . " SET " . $fields . " ";
		self::$update .= self::$where;
		return self::$update;
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