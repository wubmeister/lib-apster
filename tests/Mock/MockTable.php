<?php

use Apster\Db\Table;
use Apster\Db\Sql;

class MockTable extends Table
{
    protected $insertedRow;
    protected $updatedRow;
    protected $updatedWhere;
    protected $deletedWhere;

    public function findWith(Sql $sql)
    {
        return new MockRowset($sql, $this);
    }

    public function fetchAll()
    {
        return $this->findWith('');
    }

    public function find($where)
    {
        return $this->findWith('');
    }

    public function findOne($where)
    {
        $rowset = $this->find($where);
        return $rowset->current();
    }

    public function insert($data)
    {
        $this->insertedRow = $data;
        return 1;
    }

    public function update($data, $where)
    {
        $this->updatedRow = $data;
        $this->updatedWhere = $where;
        return 1;
    }

    public function delete($where)
    {
        $this->deletedWhere = $where;
        return 1;
    }

    protected function fetchColumns()
    {
        $this->columns = [ 'id', 'name' ];
        $this->primary = [ 'id' ];
        $this->autoIncrementColumn = 'id';
    }

    public function getInsertedRow()
    {
        return $this->insertedRow;
    }

    public function getUpdatedRow()
    {
        return $this->updatedRow;
    }

    public function getUpdatedWhere()
    {
        return $this->updatedWhere;
    }

    public function getDeletedWhere()
    {
        return $this->deletedWhere;
    }
}
