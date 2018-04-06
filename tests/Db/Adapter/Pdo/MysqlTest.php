<?php

namespace Test\Db;

use Unittest\Testcase;
use Apster\Db\Adapter\Pdo\Mysql;

class MysqlTest extends Testcase
{
    public function testCreate()
    {
        $adapter = new Mysql([
            'host' => '127.0.0.1',
            'username' => 'root',
            'password' => 'wubje318',
            'dbname' => 'apster_test'
        ]);
    }
}
