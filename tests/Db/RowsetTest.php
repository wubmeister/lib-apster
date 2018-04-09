<?php

namespace Test\Db;

include dirname(__DIR__) . "/Mock/MockDbAdapter.php";
include dirname(__DIR__) . "/Mock/MockTable.php";

use Unittest\Testcase;
use Apster\Db\Rowset;
use Apster\Db\Sql;
use Apster\Storage\Pages;
use MockDbAdapter;
use MockTable;

class RowsetTest extends Testcase
{
    protected $table;
    protected $adapter;

    public function __construct()
    {
        $this->adapter = new MockDbAdapter();
        $this->table = new MockTable('test', $this->adapter);
    }

    public function testCreate()
    {
        $sql = new Sql($this->adapter);
        $sql->select('*')->from('test');
        $rowset = new Rowset($sql, $this->table);
    }

    public function testIterateOverRows()
    {
        $sql = new Sql($this->adapter);
        $sql->select('*')->from('test');
        $rowset = new Rowset($sql, $this->table);

        $num = 0;
        foreach ($rowset as $row) {
            $this->assertEquals('Foo', $row->name);
            $num++;
        }
        $this->assertEquals(12, $num);
    }

    public function testPagination()
    {
        $sql = new Sql($this->adapter);
        $sql->select('*')->from('test');
        $rowset = new Rowset($sql, $this->table);
        $rowset->paginate(2, 10);

        $pages = $rowset->getPages();
        $this->assertIsInstanceOf(Pages::class, $pages);
        $this->assertEquals(2, $pages->pageCount);
        $this->assertEquals(2, $pages->current);
        $this->assertEquals(10, $pages->itemCountPerPage);
    }
}
