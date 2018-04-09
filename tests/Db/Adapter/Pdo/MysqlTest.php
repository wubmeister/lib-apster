<?php

namespace Test\Db;

use Unittest\Testcase;
use Apster\Db\Adapter\Pdo\Mysql;

class MysqlTest extends Testcase
{
    public function testCreate()
    {
        $config = include "../../../config.php";
        $adapterConfig = $confg['db'];
        unset($adapterConfig['adapter']);
        adapter = new Mysql($adapterConfig);

        /*
        $adapter = new Mysql([
            'host' => '127.0.0.1',
            'username' => 'root',
            'password' => 'wubje318',
            'dbname' => 'apster_test'
        ]);
        */
    }
}
