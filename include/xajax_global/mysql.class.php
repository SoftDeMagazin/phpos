<?php
class MySQL
{

    
    var $Link;
    var $Server="127.0.0.1";
    var $User="office";
    var $Pass="dantes";
    var $Db="retail_dinamic_services";
    
    function MySQL($connect = true)
    {
        if ($connect) {
            $this -> connect();
        }
    }
    
    function connect()
    {
        $this -> Link = mysql_connect($this -> Server, $this -> User, $this -> Pass)
        or die("Could not connect : " . mysql_error());
        mysql_select_db($this -> Db) or die("Could not select database");
    }
    
    function query($sql)
    {
        $result = mysql_query($sql) or die(mysql_error()." ".$sql);
        return $result;
    }
        
    function insertRow($sql)
    {
        $result = $this -> query($sql);
        $lastid = mysql_insert_id($this -> Link);
        return $lastid;
    }
    
    function numRows($sql)
    {
        $result = $this -> query($sql);
        return mysql_num_rows($result);
    }
    function tableColumns($table)
    {
        return $this -> getRows("SHOW COLUMNS FROM ". $table ."");
    }
    
    function getRow($sql)
    {
        $result = $this -> query($sql);
        if (mysql_num_rows($result)) {
            $array = mysql_fetch_array($result);
            mysql_free_result($result);
            return $array;
        } else {
            return null;
        }
    }
        
    function getRows($sql)
    {
        $result = $this -> query($sql);
        $array = null;
        $i = 0;
        while ($ar = mysql_fetch_array($result)) {
            $array[$i] = $ar;
            $i++;
        }
        mysql_free_result($result);
        return $array;
    }
    
    function getObjects($sql)
    {
        $result = $this -> query($sql);
        $array = null;
        $i = 0;
        while ($ar = mysql_fetch_object($result)) {
            $array[$i] = $ar;
            $i++;
        }
        mysql_free_result($result);
        return $array;
    }
    
    function getObject($sql)
    {
        $result = $this -> query($sql);
        $array = mysql_fetch_object($result);
        mysql_free_result($result);
        return $array;
    }
    /* object to sql */
    function insertObject(&$obj, $table, $id)
    {
        $class_vars = get_object_vars($obj);
        $sql = "INSERT INTO $table (";
        $i = 0;
        foreach ($class_vars as $name => $value) {
            if ($name != $id && $value != null) {
                if ($i==0) {
                    $sql .= "`$name`";
                } else {
                    $sql .=", `$name`";
                }
                $i++;
            }
        }
        $sql .= ") VALUES (" ;
        
        $i = 0;
        foreach ($class_vars as $name => $value) {
            if ($name != $id && $value != null) {
                if ($i==0) {
                    $sql .= "'$value'";
                } else {
                    $sql .=", '$value'";
                }
                $i++;
            }
        }
        $sql .= ");";
        $lastid = $this -> insertRow($sql);
        $getSql = "SELECT * FROM $table WHERE $id = '$lastid'";
        $obj = $this -> getObject($getSql);
    }
    
    function updateObject(&$obj, $table, $id)
    {
        $class_vars = get_object_vars($obj);
        $sql = "UPDATE $table SET ";
        $i = 0;
        foreach ($class_vars as $name => $value) {
            if ($name != $id && (!empty($value))) {
                if ($i==0) {
                    $sql .= "`$name` = '$value'";
                } else {
                    $sql .=", `$name` = '$value'";
                }
                $i++;
            }
        }
        $sql .= " WHERE $id = '". $class_vars[$id] ."'";
        $this -> query($sql);
        $getSql = "SELECT * FROM $table WHERE $id = '". $class_vars[$id] ."'";
        $obj = $this -> getObject($getSql);
    }
    
    function deleteObject($obj, $table, $id)
    {
        $class_vars = get_object_vars($obj);
        $sql = "DELETE FROM $table WHERE $id = '". $class_vars[$id] ."'";
        $this -> query($sql);
    }
        
    function arrayToSql($options = array())
    {
        $sql = "";
        if (is_array($options)) {
            if (isset($options)) {
                foreach ($options as $key => $option) {
                    if (!is_numeric($key)) {
                        $sql .= " $key ".$option;
                    } else {
                        $sql .= " ".$option;
                    }
                }
            }
        } else {
            return $options;
        }
        return $sql;
    }
    /* mysql helpers */
    function csv($options = array())
    {
        $sql = "";
        if (isset($options)) {
            $i = 0;
            foreach ($options as $key => $option) {
                if ($i) {
                    $sql .= ", ".$option;
                } else {
                    $sql .= " ".$option;
                }
                $i++;
            }
        }
        return $sql;
    }
    function between($s1, $s2)
    {
        return "BETWEEN '$s1' AND '$s2'";
    }
    function equal($s)
    {
        return "= '$s'";
    }
    function like($s)
    {
        return " LIKE '%$s%'";
    }
}
