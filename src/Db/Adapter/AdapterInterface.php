<?php

namespace Apster\Db\Adapter;

interface AdapterInterface
{
    public function beginTransaction();
    public function commit();
    public function errorCode();
    public function errorInfo();
    public function exec($statement);
    public function getAttribute($attribute);
    public function inTransaction();
    public function lastInsertId($name = null);
    public function prepare($statement, $driver_options = []);
    public function query();
    public function quote($string, $parameter_type = PDO::PARAM_STR);
    public function rollBack();
    public function setAttribute($attribute, $value);
    public function quoteIdentifier(string $identifier);
}
