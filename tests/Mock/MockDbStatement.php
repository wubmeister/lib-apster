<?php

use Apster\Db\Adapter\StatementInterface;

class MockDbStatement implements StatementInterface
{
    public $queryString;

    protected $it = 0;
    protected $fetchStyle = PDO::FETCH_BOTH;
    protected $fetchArgument = null;
    protected $fetchCtorArgs = null;
    protected $countMode = false;

    public function __construct($sql = null)
    {
        if (substr($sql, 0, 27) == "SELECT COUNT(*) AS cnt FROM") {
            $this->countMode = true;
        }
    }

    /* Methods */
    public function bindColumn($column, &$param, $type, $maxlen, $driverdata){}
    public function bindParam($parameter, &$variable, $data_type = PDO::PARAM_STR, $length, $driver_options){}
    public function bindValue($parameter, $value, $data_type = PDO::PARAM_STR){}
    public function closeCursor(){}
    public function columnCount(){}
    public function debugDumpParams(){}

    public function errorCode()
    {
        return 0;
    }

    public function errorInfo()
    {
        return [ 0, 0, '' ];
    }

    public function execute($input_parameters = null)
    {
        return true;
    }

    protected function convertRow($row, $fetchStyle)
    {
        if (!$fetchStyle) $fetchStyle = $this->fetchStyle;

        switch ($this->fetchStyle) {
            case PDO::FETCH_NUM:
                return array_values($row);
            case PDO::FETCH_BOTH:
                $result = $row;
                foreach ($row as $value) $result[] = $value;
                return $result;
            case PDO::FETCH_OBJ:
                $result = new stdClass();
                foreach ($row as $key => $value) {
                    $result->$key = $value;
                }
                return $result;
            case PDO::FETCH_CLASS:
                $className = $this->fetchArgument;
                $args = $this->fetchCtorArgs;
                $reflect = new ReflectionClass($className);
                $result = $reflect->newInstanceArgs($args);
                foreach ($row as $key => $value) {
                    $result->$key = $value;
                }
                return $result;
        }

        return $row;
    }

    public function fetch($fetch_style = null, $cursor_orientation = PDO::FETCH_ORI_NEXT, $cursor_offset = 0)
    {
        if ($this->countMode) {
            if ($this->it >= 1) {
                return null;
            }
            $this->it++;
            return $this->convertRow([ 'cnt' => 12 ], $fetch_style);
        }
        if ($this->it < 12) {
            $this->it++;
            $result = [ 'id' => $this->it, 'name' => 'Foo' ];
            return $this->convertRow($result, $fetch_style);
        }
        return null;
    }

    public function fetchAll($fetch_style = null, $fetch_argument = null, $ctor_args = [])
    {
        return [];
    }

    public function fetchColumn($column_number = 0)
    {
        return [];
    }

    public function fetchObject($class_name = "stdClass", $ctor_args)
    {
        return new $class_name;
    }

    public function getAttribute($attribute)
    {
        return null;
    }

    public function getColumnMeta($column)
    {
        return [];
    }

    public function nextRowset()
    {
        return null;
    }

    public function rowCount()
    {
        return 0;
    }

    public function setAttribute($attribute, $value){}

    public function setFetchMode($mode, $fetch_argument = null, $ctor_args = [])
    {
        $this->fetchStyle = $mode;
        $this->fetchArgument = $fetch_argument;
        $this->fetchCtorArgs = $ctor_args;
    }
}
