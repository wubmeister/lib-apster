<?php

namespace Apster\Db\Adapter;

interface StatementInterface
{
    /* Methods */
    public function bindColumn($column, &$param, $type, $maxlen, $driverdata);
    public function bindParam($parameter, &$variable, $data_type = PDO::PARAM_STR, $length, $driver_options);
    public function bindValue($parameter, $value, $data_type = PDO::PARAM_STR);
    public function closeCursor();
    public function columnCount();
    public function debugDumpParams();
    public function errorCode();
    public function errorInfo();
    public function execute($input_parameters);
    public function fetch($fetch_style = null, $cursor_orientation = PDO::FETCH_ORI_NEXT, $cursor_offset = 0);
    public function fetchAll($fetch_style = null, $fetch_argument = null, $ctor_args = []);
    public function fetchColumn($column_number = 0);
    public function fetchObject($class_name = "stdClass", $ctor_args);
    public function getAttribute($attribute);
    public function getColumnMeta($column);
    public function nextRowset();
    public function rowCount();
    public function setAttribute($attribute, $value);
    public function setFetchMode($mode, $fetch_argument = null, $ctor_args = []);
}
