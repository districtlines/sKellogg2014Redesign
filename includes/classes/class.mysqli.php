<?PHP
class SQL {
	private $data = array();
	
	public function __construct($host, $user, $pass, $db) {
		$this->conn = new mysqli($host, $user, $pass, $db);

		if (mysqli_connect_errno()) {
		    printf("Connect failed: %s\n", mysqli_connect_error());
		    exit();
		}
	}
	
	public function executeQuery($sql) {
		global $handler;
		
		$this->sql = $sql;
		
		if ($this->result = $this->conn->query($this->sql)) {
			$this->total_query++;		
			return true;
		} else {			
			debug_print_backtrace();
			die("\n\n\nError in SQL Query: {$this->sql}\nMessage: " . $sqlError);
			return false;
		}
	}
	
	public function launchQuery($sql) {
		$arResult = array();

/* 		$func = create_function('$v', 'return is_string($v) ? $v != null ? stripslashes($v) : "null" : $v;');		 */
		$func = create_function('$v', 'return (is_string($v) && $v != NULL) ? stripslashes($v) : $v;');

		$this->executeQuery($sql);

		$this->total_query++;
			
		while ($row = mysqli_fetch_array($this->result)) {
			$arResult[] = array_map($func, $row);
		}
		
		return $arResult;
	}
	
	public function fetchRow($sql) {
		$arResult = array();
		
		$func = create_function('$v', 'return is_string($v) ? $v != null ? stripslashes($v) : "null" : $v;');		
//		$func = create_function('$v', 'return (is_string($v) && $v != NULL) ? stripslashes($v) : $v;');

		
		$this->executeQuery($sql);
		if ($this->result->num_rows == 1) {
			$this->total_query++;
			$row = mysqli_fetch_assoc($this->result);
			
			return array_map($func, $row);
		} else {
			return false;
		}
	}
	
	public function fetchAssoc($sql) {
		$arResult = array();
		$func = create_function('$v', 'return is_string($v) ? $v != null ? stripslashes($v) : "null" : $v;');		
//		$func = create_function('$v', 'return (is_string($v) && $v != NULL) ? stripslashes($v) : $v;');
		
		
//		return is_string($v) ? $v != null ? stripslashes($v) : 'null' : $v;

		$this->executeQuery($sql);

		$this->total_query++;
			
		while ($row = mysqli_fetch_assoc($this->result)) {
			$arResult[] = array_map($func, $row);
		}
		
		return $arResult;
	}
	
	public function insert($table, $parameters, $commit = true) {
		$error = false;
		
		$fields = array();
		$values = array();
		
		foreach ($parameters as $k => $v) {
			$fields[] = $k;
			$values[] = $this->processToDB($v);
		}
		
		$query = "INSERT INTO $table (";
		
		foreach ($fields as $f) {
			$query .= "$f,";
		}
		
		$query  = rtrim($query, ',');
		$query .= ") VALUES (";
				
		foreach ($values as $v) {
						
		
			$query .= ($v != 'NOW()') ? $v != null ? "'{$v}', " : "null, " : "{$v}, ";
			
		}

		$query  = rtrim($query, ', ');
		$query .= ")";
	
		if ($commit) return ($this->executeQuery($query)); else return ($query);
	}
	
	public function update($table, $parameters, $where, $commit = true){
		$error = false;
		
		$query = "UPDATE {$table} SET ";
		
		foreach ($parameters as $k => $v) {
			$query .= ($v != 'NOW()') ? "{$k} = '" . $this->processToDB($v) . "', " : "{$k} = " . $this->processToDB($v) . ", ";
		}
		
		$query = ($where) ? rtrim($query, ', ') . " WHERE {$where}" : rtrim($query, ', ');
		
		if ($commit) return ($this->executeQuery($query)); else return ($query);
	}
	
	private function processToDB($v) {
		return (is_string($v) && $v != NULL) ? mysqli_escape_string($this->conn,$v) : $v;
	}
		
	public function getLastID() {
		return mysqli_insert_id($this->conn);
	}
	
	public function getTotalQueries() {
		return '<!-- Total Queries: ' . $this->total_query . ' -->';
	}
	
	public function __destruct() {
		$this->conn->close();
	}
	
	public function __get($n) {
		return (isset($this->data[$n])) ? $this->data[$n] : false;
	}
	
	public function __set($n, $v) {
		$this->data[$n] = $v;
	}
	
	public function __toString() {
		return ($this->sql);
	}
}

?>