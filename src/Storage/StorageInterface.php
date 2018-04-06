<?php

namespace Apster\Storage;

interface StorageInterface
{
    public function fetchAll();
    public function find($where);
    public function findOne($where);
    public function insert($data);
    public function update($data, $where);
    public function delete($where);
}
