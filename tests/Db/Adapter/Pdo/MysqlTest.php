<?php

namespace Test\Db;

use Unittest\Testcase;
use Apster\Db\Adapter\Pdo\Mysql;

class MysqlTest extends Testcase
{
    public function testCreate()
    {
        $config = include dirname(dirname(dirname(__DIR__))) . "/config.php";
        $adapterConfig = $config['db'];
        unset($adapterConfig['adapter']);
        $adapter = new Mysql($adapterConfig);
    }
}
