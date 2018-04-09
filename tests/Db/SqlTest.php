<?php

namespace Test\Db;

include dirname(__DIR__) . "/Mock/MockDbAdapter.php";

use Unittest\Testcase;
use Apster\Db\Sql;

class SqlTest extends Testcase
{
    protected $adapter;

    public function __construct()
    {
        $this->adapter = new \MockDbAdapter();
    }

    function testSelectFromWhereOrder()
    {
        $sql = (new Sql($this->adapter))->select('*')->from('my_table');
        $sqlString = (string)$sql;
        $this->assertEquals("SELECT * FROM my_table", $sqlString);

        $sql = (new Sql($this->adapter))->select([ 'id', 'cnt' => 'COUNT(*)' ])->from('my_table');
        $sqlString = (string)$sql;
        $this->assertEquals("SELECT id, COUNT(*) AS cnt FROM my_table", $sqlString);

        $sql = (new Sql($this->adapter))->select('*')->from([ 'a' => 'my_table' ]);
        $sqlString = (string)$sql;
        $this->assertEquals("SELECT * FROM my_table AS a", $sqlString);

        $sql = (new Sql($this->adapter))->select('*')->from('my_table')->where('id = :id');
        $sqlString = (string)$sql;
        $this->assertEquals("SELECT * FROM my_table WHERE (id = :id)", $sqlString);

        $sql = (new Sql($this->adapter))->select('*')->from('my_table')->where([ 'id' => 2, 'name' => 'Foobar' ]);
        $sqlString = (string)$sql;
        $this->assertEquals("SELECT * FROM my_table WHERE (id = :id AND name = :name)", $sqlString);

        $sql = (new Sql($this->adapter))->select('*')->from('my_table')->order('name ASC');
        $sqlString = (string)$sql;
        $this->assertEquals("SELECT * FROM my_table ORDER BY name ASC", $sqlString);

        $sql = (new Sql($this->adapter))->select('*')->from('my_table')->order([ 'name ASC', 'date DESC' ]);
        $sqlString = (string)$sql;
        $this->assertEquals("SELECT * FROM my_table ORDER BY name ASC, date DESC", $sqlString);

        $sql = (new Sql($this->adapter))->select('*')->from('my_table')->where('id = :id')->order('name ASC');
        $sqlString = (string)$sql;
        $this->assertEquals("SELECT * FROM my_table WHERE (id = :id) ORDER BY name ASC", $sqlString);
    }

    function testLimit()
    {
        $sql = (new Sql($this->adapter))->select('*')->from('my_table')->limit(10, 2);
        $sqlString = (string)$sql;
        $this->assertEquals("SELECT * FROM my_table LIMIT 10, 2", $sqlString);
    }
}
