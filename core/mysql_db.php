<?php
class mysql_db {
    private $conn;
    public static $queries_count = 0;
    
    public function __construct() {
		$this->conn = mysql_connect(DBHOST, DBUSERNAME, DBPASSWORD) or die(mysql_error());
		mysql_select_db(DBNAME, $this->conn);
    }
    
    function select($p) {			
	    $query = "SELECT ";

        if (!isset($p['select'])) {
            $query .= "*";
        } else {
            $query .= $p['select'];
        }
	
        if (!isset($p['from'])) {
            $query .= " FROM ".$p['table'];
        } else {
            $query .= " FROM ".$p['from'];
        }
	
        if (isset($p['where'])) {
            $query .= " WHERE ".$p['where'];
        } else if (isset($p["key"]) and !isset($p["quantity"])) {
            $query .= " WHERE id = ".$p["key"];
        }

        if (isset($p['or_where'])) {
            $query .= " OR WHERE ".$p['where'];
        } else if (isset($p["key"]) and !isset($p["quantity"])) {
            $query .= " OR WHERE id = ".$p["key"];
        }
	
        if (isset($p["order"])) {
            $query .= " ORDER BY ".$p["order"];
        } else if (isset($p['arrange'])) {
            $query .= " ORDER BY id ".$p['arrange'];
        }

        if (isset($p['limit'])) {
            $query .= ' LIMIT '.$p['limit']; 
        }
	
        if (isset($p['page'])) {
            $pages = ceil($this->query($query)->size() / ROWSPERPAGE);
            if($_GET['page'] > $pages) {
                $_GET['page'] = $pages;
            }
            if($_GET['page'] < 1) {
                $_GET['page'] = 1;
            }
            $page = $_GET['page'];
            $_SESSION['pages'] = $pages;
            $query .= " LIMIT ".(($_GET['page'] - 1) * ROWSPERPAGE).", ".ROWSPERPAGE;
        }
        if(isset($p['debug'])) {
            print $query;
        }
	    return $this->query($query);
    }
    
    public function query($sql) {
        #print $sql;
        if(!$result = mysql_query($sql)) {
            trigger_error('Error en el query: '. mysql_error($this->conn));
        }
            self::$queries_count += 1;
            return new mysql_result($this->conn, $result);
        }
    
    function noQuery($sql) {
        if(mysql_query($sql, $this->conn)) {
            return true;
        } else {
            return false;
        }
    }
	
    function insert($dataNames, $dataValues, $tableName) {
    $sqlValues = '';
	$sqlNames = "INSERT INTO ".$tableName. "(";
	for($x=0; $x < count($dataNames); $x++) {
		if ($x != count($dataNames) -1) {
			$sqlNames = $sqlNames . $dataNames[$x].", ";
			$sqlValues = $sqlValues . "'" . $dataValues[$x] ."', ";
		} else {
			$sqlNames = $sqlNames . $dataNames[$x]. ") VALUES (";
			$sqlValues = $sqlValues . "'" . $dataValues[$x] . "')";
		}
	}
	return $this->noQuery($sqlNames . $sqlValues);
    }
	
    function update($dataNames, $dataValues, $tableName, $condition) {
	$sql = "UPDATE ".$tableName." SET ";
	for($x=0; $x < count($dataNames); $x++) {
		if($x != count($dataNames) -1 ) {
			$sql = $sql . $dataNames[$x] . "= '" . $dataValues[$x] . "', ";
		} else {
			$sql = $sql . $dataNames[$x] . "= '" . $dataValues[$x] . "' ";
		}
	}
	$sql = $sql . $condition;
	#echo $sql;
	return $this->noQuery($sql);
    }
    
    public function insertId() {
        return mysql_insert_id($this->conn);
    }
}

class mysql_result {
    private $conn;
    private $result;
    
    public function __construct($db, $result) {
	$this->conn = $db;
	$this->result = $result;
    }
    
    public function fetch() {
		if ($row = mysql_fetch_array($this->result, MYSQL_ASSOC)) {
			return $row;
		} else if ( $this->size() > 0 ) {
			mysql_data_seek($this->result, 0);
			return false;
		} else {
			return false;
		}
    }
	
	public function fetch_single_object() {
		if ($row = mysql_fetch_object($this->result)) {
			return $row;
		} else if ( $this->size() > 0 ) {
			mysql_data_seek($this->result, 0);
			return false;
		} else {
			return false;
		}
    }
    
    public function size() {
	return mysql_num_rows($this->result);
    }
    
    public function isBlank() {
	return $this->size() == 0; 
    }
    
    public function getPages() {
	return ceil($this->size() / ROWSPERPAGE);
    }
	
	public function fetch_array() {
	$data_array = array();
	while($r = mysql_fetch_array($this->result)) {
	    $data_array[] = $r;
	}
	return $data_array;
    }
    
    public function fetch_object() {
	$data_array = array();
	while($r = mysql_fetch_object($this->result)) {
	    $data_array[] = $r;
	}
	return $data_array;
    }
}
?>