<?
	/*
	
			close_database
	
	*/
	function close_database() {
		global $link;
		if ($link) {
			mysql_close($link);
		}
	}
	
	
	
	
	/*
	
			get_rows
	
	*/
	function get_rows($sql = null,$debug = false) {
		static $rs;
		global $get_rows_count,$get_rows_total;
		
		
		//if there is a new query, get started
		if ($sql) {
			if ($debug) {
				print $sql;
			}
			
			$get_rows_count = 0;
			
			//run the query
			$rs = mysql_query($sql) or die(mysql_error() . ' -- ' . $sql);
			
			//if it fails or there are no rows, return false
			if ($rs && mysql_num_rows($rs)) {
				$get_rows_total = mysql_num_rows($rs);
				
				return true;
			} else {
				return false;
			}
		}
		
		
		//get the next row, if it exists, and return it
		$row = mysql_fetch_array($rs);
		
		if ($row) {
			$get_rows_count++;
			
			foreach ($row as $field => $value) {
				$row[$field] = stripslashes($value);
			}
			
			return $row;
		} else {
			return false;
		}
	}
	
	
	
	
	/*
	
			get_one
	
	*/
	function get_one($sql = null,$debug = false) {
		
		if ($sql) {
			if ($debug) {
				print $sql;
			}
			
			//run the query
			$rs = mysql_query($sql) or die(mysql_error() . ' -- ' . $sql);
			
			//if it fails or there are no rows, return false
			if ($rs && mysql_num_rows($rs)) {
				return mysql_result($rs, 0);
			} else {
				return false;
			}
		}
	}
	
	
	
	
	/*
	
			get_all
	
	*/
	function get_all($sql = null) {
		static $rs;
		
		$debug = false;
		
		
		//if there is a new query, get started
		if ($sql) {
			if ($debug) {
				print $sql;
			}
			
			//run the query
			$rs = mysql_query($sql) or die(mysql_error() . ' -- ' . $sql);
			
			//if it fails or there are no rows, return false
			if ($rs && mysql_num_rows($rs)) {
				return true;
			} else {
				return false;
			}
		}
		
		
		//get all of the rows and return them
		$row = array();
		$rows = array();
		
		while ($row = mysql_fetch_array($rs)) {
			foreach ($row as $field => $value) {
				$row[$field] = stripslashes($value);
			}

			$rows[] = $row;
		}
		
		return $rows;
	}
	
	
	
	
	/*
	
			get_col
	
	*/
	function get_col($sql = null) {
		$debug = false;
		
		
		//if there is a new query, get started
		if ($sql) {
			if ($debug) {
				print $sql;
			}
			
			//run the query
			$rs = mysql_query($sql) or die(mysql_error() . ' -- ' . $sql);
			
			//if it fails, return false
			if (!$rs) {
				return false;
			}
		}
		
		
		//get all of the rows and return them
		$row = array();
		$rows = array();
		
		while ($row = mysql_fetch_array($rs)) {
			if ($row[1]) {
				$rows[$row[0]] = stripslashes($row[1]);
			} else {
				$rows[] = stripslashes($row[0]);
			}
		}
		
		return $rows;
	}
	
	
	
	
	/*
	
			save_row
	
	*/
	function save_row($table,$data,$where = '') {
		$sql = '';
		$sql_data = array();
		
		//if we don't have a table, exit
		if (!$table) {
			return false;
		}
		
		//if we don't have a data array, exit
		if (!is_array($data)) {
			return false;
		}
		
		
		foreach ($data as $field => $value) {
			if ($field && !is_numeric($field)) {
/*
// what is this code?!??!
if(strstr($field, "time") || strstr($field, "date")) {
				$value = strtotime($value);
				}
*/
				$value = strip_tags($value, '<p><table><tr><td><tbody><thead><th><tfoot><a><b><strong><i><u><s><strike><img><br><br/><span>');
				$_data[$field] = $value;
			}
		}
		
		$data = $_data;
		

		//are we updating a row?
		if ($where) {
			//put together the data
			foreach ($data as $field => $value) {
				//$value = mysql_real_escape_string($value);
				$sql_data[] = '`' . $field . '` = "' . $value . '"';
			}
			mysql_query('UPDATE `' . $table . '` SET ' . implode(',',$sql_data) . ' WHERE ' . $where) or die(mysql_error() . ' -- ' . $sql);
			return true;
		} else {
			mysql_query('INSERT INTO `' . $table . '` (`' . implode('`,`',array_keys($data)) . '`) VALUES (\'' . implode('\',\'',$data) . '\')') or die(mysql_error() . ' -- ' . $sql);
			return true;
		}
	}
	
?>