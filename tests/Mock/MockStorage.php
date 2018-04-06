<?php

use Storage\StorageInterface;

class MockStorage implements StorageInterface
{
    public function fetchAll()
    {
        return new MockResultset();
    }

    public function find($where)
    {
        return new MockResultset();
    }

    public function findOne($where)
    {
        return new MockResult();
    }

    public function insert($data)
    {
        return 1;
    }

    public function update($data, $where)
    {
        return 1;
    }

    public function delete($where)
    {
        return 1;
    }
}
