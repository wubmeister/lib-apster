<?php

use Apster\Db\Adapter\AdapterInterface;

class MockDbAdapter implements AdapterInterface
{
    public function beginTransaction(){}
    public function commit(){}
    public function errorCode()
    {
        return 0;
    }

    public function errorInfo()
    {
        return [ 0, 0, '' ];
    }

    public function exec($statement)
    {
        return new MockDbStatement();
    }

    public function getAttribute($attribute)
    {
        return null;
    }

    public function inTransaction()
    {
        return false;
    }

    public function lastInsertId($name = null)
    {
        return 1;
    }

    public function prepare($statement, $driver_options = [])
    {
        return new MockDbStatement();
    }

    public function query()
    {
        return new MockDbStatement();
    }

    public function quote($string, $parameter_type = PDO::PARAM_STR)
    {
        return str_replace("'", "''", $string);
    }

    public function rollBack(){}

    public function setAttribute($attribute, $value){}

    public function quoteIdentifier(string $identifier)
    {
        return $identifier;
    }
}
