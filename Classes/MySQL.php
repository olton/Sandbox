<?php

namespace Classes;

define('MSG_DATABASE_PROVIDER_CONFIG_EMPTY', 'Config is empty');
define('MSG_DATABASE_PROVIDER_NO_CONNECT', 'Not connected');
define('MSG_DATABASE_PROVIDER_SQL_EMPTY', 'SQL is empty');
define('MSG_DATABASE_PROVIDER_SQL_ERROR', 'SQL error');
define('MSG_DATABASE_PROVIDER_NO_DATA', 'No data');
define('MSG_DATABASE_PROVIDER_NOT_SUPPORTED', 'Not supported');

class MySQL {
    public $queries;
    public $stack;
    private $conn;
    private $query;
    public $snapshot;
    public $sql_error;

    private $host = "";
    private $port = 3306;
    private $user = "";
    private $password = "";
    private $schema = "";
    private $charset = "utf8";
    private $fetch = MYSQLI_ASSOC;
    private $config;

    /**
     * MySQL constructor.
     * @param $config
     * @throws \Exception
     */
    public function __construct($config){
        if (empty ($config)) {
            throw new \Exception(MSG_DATABASE_PROVIDER_CONFIG_EMPTY, E_USER_ERROR);
        }
        $this->config = $config;
        $this->host = isset($config['host'])?$config['host']:'localhost';
        $this->port = isset($config['port'])?$config['port']:3306;
        $this->user = isset($config['user'])?$config['user']:'root';
        $this->password = isset($config['password'])?$config['password']:'';
        $this->charset = isset($config['charset'])?$config['charset']:'utf8';
        $this->schema = isset($config['schema'])?$config['schema']:'test';
        $this->fetch = isset($config['fetch'])?$config['fetch']:MYSQLI_ASSOC;

        $this->Connect();
        $this->SetCharset($this->charset);
        $this->SetSchema($this->schema);
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function Connect(){
        $this->conn = mysqli_connect($this->host, $this->user, $this->password, $this->schema, $this->port);

        if (!$this->conn) {
            throw new \Exception(MSG_DATABASE_PROVIDER_NO_CONNECT, E_USER_ERROR);
        }

        return $this;
    }

    public function Reconnect(){
        $this->Disconnect();
        $this->Connect();
    }

    public function Disconnect(){
        if ($this->conn){
            mysqli_close($this->conn);
        }
    }

    public function SetCharset($charset = false){
        $charset = $charset?$charset:$this->charset;
        if ($this->conn)
            mysqli_set_charset($this->conn, $charset);
    }

    public function SetSchema($schema = false){
        $schema = $schema?$schema:$this->schema;
        if ($this->conn)
            mysqli_select_db($this->conn, $schema);
    }

    public function SetFetchMode($mode = MYSQLI_ASSOC){
        $this->fetch = $mode;
    }

    public function Escape($value){
        return $this->_escape($value);
    }

    protected function _escape($value){
        if (!isset($value)) {
            return 'NULL';
        }

        if (get_magic_quotes_gpc()) {
            $value = stripslashes($value);
        }
        if (!is_numeric($value) && (strtoupper($value) !== 'NULL')) {
            $value = "'" . mysqli_real_escape_string($this->conn, $value) . "'";
        }
        return $value;
    }

    /**
     * @param $sql
     * @return bool|\mysqli_result
     * @throws \Exception
     */
    private function _execSQL($sql){
        $this->queries[] = $sql;

        if (empty ($sql)) {
            throw new \Exception(MSG_DATABASE_PROVIDER_SQL_EMPTY, E_USER_ERROR);
        }
        $sql = trim($sql);
        $this->snapshot = $sql;

        try {
            $this->query = mysqli_query($this->conn, $sql);
        } catch (\Exception $e) {
            $this->sql_error = mysqli_error($this->conn);
            Debug::Stop($e->getMessage() . "\n<br />".$sql);
        }

        if ($this->query === false) {
            throw new \Exception(MSG_DATABASE_PROVIDER_SQL_ERROR . " [".(mysqli_errno($this->conn))."] " . " $sql " . (mysqli_error($this->conn)), E_USER_ERROR);
        }

        $sql_type = strtoupper(substr($sql, 0, strpos($sql, " ")));

        $rows = ($sql_type == "SELECT") ? mysqli_num_rows($this->query) : mysqli_affected_rows($this->conn);

        $key = md5($sql);

        $this->stack[$key]['rows'] = $rows;
        $this->stack[$key]['sql'] = $sql;
        $this->stack[$key]['type'] = $sql_type;
        return $this->query;
    }

    public function Select($sql){
        return $this->_execSQL($sql);
    }

    /**
     * @param $table
     * @param $data
     * @param bool $condition
     * @param bool $autoescape
     * @return bool|\mysqli_result
     * @throws \Exception
     */
    public function Update($table, $data, $condition = false, $autoescape = false){
        if (empty ($data)) {
            throw new \Exception(MSG_DATABASE_PROVIDER_NO_DATA, E_USER_ERROR);
        }
        $arr = array();
        $sql = "update $table set";
        foreach($data as $key=>$value){
            $arr[] = "`$key` = " . ( $autoescape ? $this->_escape($value) : $value );
        }
        $sql .= " " . join(", ", $arr);
        if ($condition) {
            $sql .= " where $condition";
        }
        return $this->_execSQL($sql);
    }

    public function Delete($table, $condition = false){
        $sql = "delete from " . $table;
        if ($condition) {
            $sql .= " where $condition";
        }
        return $this->_execSQL($sql);
    }

    /**
     * @param $table
     * @param array $data
     * @param array or false $on_duplicate_key
     * @param bool $ignore
     * @param bool $autoescape
     * @return bool|\mysqli_result
     * @throws \Exception
     */
    public function Insert($table, $data, $on_duplicate_key = null, $ignore = false, $autoescape = false){
        if (empty($data)) {
            throw new \Exception(MSG_DATABASE_PROVIDER_NO_DATA, E_USER_ERROR);
        }
        $test_key = true;
        $f = array();
        $v = array();
        $sql = "insert ".($ignore ? 'ignore':'')." into $table";
        foreach($data as $key=>$value){
            if (is_numeric($key)) $test_key = false;
            $f[] = "`$key`";
            $v[] = $autoescape ? $this->_escape($value) : $value;
        }
        if ($test_key) {
            $sql .= "(".join(", ", $f).")";
        }
        $sql .= " values(".join(", ", $v).")";

        if (is_array($on_duplicate_key)){
            $sql .= "on duplicate key update ";
            if (is_array($on_duplicate_key)) {
                $on_a = array();
                foreach($on_duplicate_key as $key=>$value){
                    $on_a[] = "`$key` = " . ( $autoescape ? $this->_escape($value) : $value );
                }
                $sql .= join(", ", $on_a);
            } else {
                $sql .= $on_duplicate_key;
            }
        }

        return $this->_execSQL($sql);
    }

    public function Rows($handle = null){
        $handler = $handle?$handle:$this->query;
        return ($handler === true) ? mysqli_affected_rows($this->conn) : mysqli_num_rows($handler);
    }

    public function Columns($table){
        $columns = array();
        $current_fetch_mode = $this->fetch;
        $this->SetFetchMode(MYSQLI_ASSOC);
        $sql = "DESC $table";
        $h = $this->Select($sql);
        while($row = $this->FetchArray($h)){
            $columns[] = $row['Field'];
        }
        $this->SetFetchMode($current_fetch_mode);
        return $columns;
    }

    public function ID(){
        return $this->conn ? mysqli_insert_id($this->conn) : false;
    }

    public function FetchArray($handle = null){
        $handle = $handle?$handle:$this->query;
        $result = mysqli_fetch_array($handle, $this->fetch);
        return $result;
    }

    public function FetchObject($handle = null, $class = null){
        $handle = $handle?$handle:$this->query;
        return mysqli_fetch_object($handle, $class);
    }

    public function FetchAll($handle = false){
        $result = array();
        while($row = $this->FetchArray($handle)){
            $result[] = $row;
        }
        return $result;
    }

    public function FetchResult($handle = false, $row = 0, $field = 0){
        $handle = $handle?$handle:$this->query;
        $current_fetch_mode = $this->fetch;
        $this->SetFetchMode(MYSQLI_NUM);
        $result = $this->FetchAll($handle);
        $this->SetFetchMode($current_fetch_mode);
        $val = isset($result[$row][$field]) ? $result[$row][$field] : false;
        return $val;
    }

    public function Fetch($handle = false, $how = 'ARRAY'){
        $handle = $handle?$handle:$this->query;
        switch($how){
            case 'OBJECT': {
                $result = $this->FetchObject($handle);
                break;
            }
            case 'ALL': {
                $result = $this->FetchAll($handle);
                break;
            }
            default: {
                $result = $this->FetchArray($handle);
            }
        }
        return $result;
    }

    public function Transaction(){
        return $this->Select('START TRANSACTION');
    }

    public function Commit(){
        return $this->Select('COMMIT');
    }

    public function Rollback(){
        return $this->Select('ROLLBACK');
    }

    public function ExecProc($name, $params){
        $_outs = array();
        if (!empty($params)) {
            foreach($params as &$par){
                $par = trim($par);
                if ((strlen($par) > 0) && ($par[0] == "@")) {
                    $_outs[] = $par;
                } else {
                    $par = $this->Escape( $par );
                }
            }
            $_p = join(",", $params);
        } else {
            $_p = "";
        }
        $query_call = "call $name($_p);";
        $h = $this->Select($query_call);
        if ($h) {
            if (count($_outs) == 0) return true;
            $sql = "select " . join(", ", $_outs);
            $h = $this->Select($sql);
            return $this->FetchArray($h);
        } else {
            return false;
        }
    }

    public function ExecFunc($name, $params){
        if (is_array($params)) {
            foreach($params as &$par){
                $par = trim($par);
                $par = $this->Escape( $par );
            }
            $_p = join(",", $params);
        } else {
            $_p = "";
        }
        $query_call = "select $name($_p) as result";
        $this->queries[] = $query_call;
        $h = $this->Select($query_call);
        $result = $this->FetchArray($h);
        return $result['result'];
    }

    public function MultiQuery($sql)
    {
        if(mysqli_multi_query($this->conn, $sql)){
            do {
                if ($result = mysqli_store_result($this->conn)) {
                    mysqli_free_result($result);
                }
            } while (mysqli_more_results($this->conn) && mysqli_next_result($this->conn));
            return true;
        }
        return false;
    }

    public function Stack(){
        return [
            "sql" => $this->query,
            "error" => $this->sql_error,
            "stack" => $this->stack,
            "snapshot" => $this->snapshot
        ];
    }
}
