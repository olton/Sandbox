<?php

namespace Classes;

class Model extends Super {
    protected $provider;
    protected $found_rows = 0;
    public $page_size = 20;

    public function RowsFound()
    {
        return $this->found_rows;
    }

    public function __construct(MySQL $provider = null){

        if ($provider) {
            $this->provider = $provider;
        } else {
            if (isset($GLOBALS['database']['provider'])) {
                $this->provider = $GLOBALS['database']['provider'];
            } else {
                throw new \Exception("Provider is not defined", E_USER_ERROR);
            }
        }
    }

    public function __destruct () {
        unset($this->provider);
    }

    /**
     * @return mixed
     */
    public function GetProvider(){
        return $this->provider;
    }

    /**
     * @param $sql
     * @return mixed
     */
    public function Select($sql){
        return $this->provider->Select($sql);
    }

    /**
     * @param $table
     * @param $data
     * @param bool $condition
     * @param bool $escape
     * @return mixed
     */
    public function Update($table, $data, $condition = false, $escape = false){
        return $this->provider->Update($table, $data, $condition, $escape);
    }

    /**
     * @param $table
     * @param $data
     * @param null or array $on_duplicate_key
     * @param bool $ignore
     * @param bool $escape
     * @return mixed
     */
    public function Insert($table, $data, $on_duplicate_key = null, $ignore = false, $escape = false){
        return $this->provider->Insert($table, $data, $on_duplicate_key, $ignore, $escape);
    }

    /**
     * @param $table
     * @param bool $condition
     * @return mixed
     */
    public function Delete($table, $condition = false){
        return $this->provider->Delete($table, $condition);
    }

    /**
     * @param $name
     * @param $params
     * @return mixed
     */
    public function ExecProc($name, $params){
        return $this->provider->ExecProc($name, $params);
    }

    /**
     * @param $name
     * @param $params
     * @return mixed
     */
    public function ExecFunc($name, $params){
        return $this->provider->ExecFunc($name, $params);
    }

    /**
     * @param null $handle
     * @return mixed
     */
    public function Rows($handle = null){
        return $this->provider->Rows($handle);
    }

    /**
     * @param $table
     * @return mixed
     */
    public function Columns($table){
        return $this->provider->Columns($table);
    }

    /**
     * @return mixed
     */
    public function ID(){
        return $this->provider->ID();
    }

    /**
     * @param bool $handle
     * @param string $how
     * @return mixed
     */
    public function Fetch($handle = false, $how = 'ARRAY'){
        return $this->provider->Fetch($handle, $how);
    }

    /**
     * @param bool $handle
     * @return mixed
     */
    public function FetchArray($handle = false){
        return $this->provider->FetchArray($handle);
    }

    /**
     * @param bool $handle
     * @param bool $class
     * @return mixed
     */
    public function FetchObject($handle = false, $class = false){
        return $this->provider->FetchObject($handle, $class);
    }

    /**
     * @param bool $handle
     * @return mixed
     */
    public function FetchAll($handle = false){
        return $this->provider->FetchAll($handle);
    }

    /**
     * @param bool $handle
     * @param int $row
     * @param int $field
     * @return mixed
     */
    public function FetchResult($handle = false, $row = 0, $field = 0){
        return $this->provider->FetchResult($handle, $row, $field);
    }

    /**
     * @return mixed
     */
    public function Transaction(){
        return $this->provider->Transaction();
    }

    /**
     * @return mixed
     */
    public function Commit(){
        return $this->provider->Commit();
    }

    /**
     * @return mixed
     */
    public function Rollback(){
        return $this->provider->Rollback();
    }

    /**
     * @param $val
     * @return mixed
     */
    public function _e($val) {
        return $this->provider->Escape($val);
    }

    /**
     * @param $sql
     * @param string $delimiter
     * @return bool
     */
    public function ExecScript($sql, $delimiter = ";")
    {
        $scripts = explode($delimiter, $sql);
        if (empty($scripts)) return false;

        foreach($scripts as $script){
            if (strlen(trim($script)) == 0) continue;
            $this->Select($script);
        }
        return true;
    }

    /**
     * @param null $page
     * @return string
     */
    public function Limit($page = null){
        $start = (!$page ? 0 : $page) * $this->page_size - $this->page_size;
        $start = ($start < 0) ? 0 : $start;
        $limit = $start >= 0 ? "limit $start, {$this->page_size}" : "limit {$this->page_size}";
        //var_dump($limit);
        return $limit;
    }

    /**
     * @param $table
     * @param $field
     * @param $value
     * @param string $operator
     * @return bool
     */
    public function CheckValue($table, $field, $value, $operator = "=")
    {
        $sql = "select count(*) from " . $table . " where " . $field . $operator . $this->_e($value);
        $h = $this->Select($sql);
        return $this->FetchResult($h, 0, 0) > 0;
    }

    /**
     * @param $sql
     * @return mixed
     */
    public function MultiQuery($sql)
    {
        return $this->provider->MultiQuery($sql);
    }

    /**
     * @return mixed
     */
    public function Stack()
    {
        return $this->provider->Stack();
    }
}
