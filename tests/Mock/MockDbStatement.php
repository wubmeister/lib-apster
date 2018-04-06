<?php

use Db\Adapter\StatementInterface;

class MockDbStatement implements StatementInterface
{
    public $queryString;

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

    public function execute($input_parameters)
    {
        return true;
    }

    public function fetch($fetch_style, $cursor_orientation = PDO::FETCH_ORI_NEXT, $cursor_offset = 0)
    {
        return [];
    }

    public function fetchAll($fetch_style, $fetch_argument, $ctor_args = [])
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
    public function setFetchMode($mode){}
}
