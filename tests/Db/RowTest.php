<?php

namespace Test\Db;

include dirname(__DIR__) . "/Mock/MockDbAdapter.php";
include dirname(__DIR__) . "/Mock/MockTable.php";

use Unittest\Testcase;
use Apster\Db\Row;
use MockDbAdapter;
use MockTable;

class RowTest extends Testcase
{
    protected $table;

    public function __construct()
    {
        $this->table = new MockTable('test', new MockDbAdapter());
    }

    public function testCreate()
    {
        $row = new Row($this->table);
        $row->name = 'Test';
        $row->save();

        $insertedData = $this->table->getInsertedRow();
        $this->assertEquals(1, $row->id);
        $this->assertEquals('Test', $insertedData['name']);
    }

    public function testUpdate()
    {
        $row = new Row($this->table, true);
        $row->id = 12;
        $row->name = 'Test';
        $row->save();

        $updatedData = $this->table->getUpdatedRow();
        $updatedWhere = $this->table->getUpdatedWhere();
        $this->assertEquals('Test', $updatedData['name']);
        $this->assertEquals(12, $updatedWhere['id']);
    }

    public function testDelete()
    {
        $row = new Row($this->table, true);
        $row->id = 12;
        $row->delete();

        $deletedWhere = $this->table->getDeletedWhere();
        $this->assertEquals(12, $deletedWhere['id']);
    }
}
