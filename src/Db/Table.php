<?php

namespace Apster\Db;

use PDO;
use Apster\Storage\StorageInterface;

class Table implements StorageInterface
{
    protected $name;
    protected $adapter;
    protected $columns;
    protected $autoIncrementColumn;
    protected $primary;
    protected $rowClass = Row::class;

    public function __construct(string $name, Adapter\AdapterInterface $adapter, $rowClass = null)
    {
        $this->name = $name;
        $this->adapter = $adapter;
        if ($rowClass) {
            $this->rowClass = (string)$rowClass;
        }
    }

    public function getAdapter()
    {
        return $this->adapter;
    }

    public function getRowClass()
    {
        return $this->rowClass;
    }

    public function findWith(Sql $sql)
    {
        return new Rowset($sql, $this);
    }

    public function fetchAll()
    {
        $sql = (new Sql($this->adapter))
            ->select('*')
            ->from($this->name);
        return $this->findWith($sql, $this);
    }

    public function find($where)
    {
        $sql = (new Sql($this->adapter))
            ->select('*')
            ->from($this->name)
            ->where($where);
        return $this->findWith($sql, $this);
    }

    public function findOne($where)
    {
        $rowset = $this->find($where);
        return $rowset->current();
    }

    public function insert($data)
    {
        $sql = (new Sql($this->adapter))
            ->insert($this->name)
            ->values($data);

        $stmt = $sql->prepare($this->adapter);
        if (!$stmt->execute()) {
            $err = $stmt->errorInfo();
            throw new DatabaseException($err[2]);
        }

        return $this->adapter->lastInsertId;
    }

    public function update($data, $where)
    {
        $sql = (new Sql($this->adapter))
            ->update($this->name)
            ->set($data)
            ->where($where);

        $stmt = $sql->prepare($this->adapter);
        if (!$stmt->execute()) {
            $err = $stmt->errorInfo();
            throw new DatabaseException($err[2]);
        }

        return $stmt->rowCount();
    }

    public function delete($where)
    {
        $sql = (new Sql($this->adapter))
            ->delete($this->name)
            ->where($where);

        $stmt = $sql->prepare($this->adapter);
        if (!$stmt->execute()) {
            $err = $stmt->errorInfo();
            throw new DatabaseException($err[2]);
        }

        return $stmt->rowCount();
    }

    protected function fetchColumns()
    {
        $sql = "SHOW COLUMNS FROM {$this->name}";
        $stmt = $this->adapter->query($sql);

        if (!$stmt) {
            $err = $this->adapter->errorInfo();
            throw new DatabaseException($err[2]);
        }

        $this->columns = [];
        $this->primary = [];
        while ($column = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            $this->columns[] = $column['Field'];
            if ($column['key'] == 'PRI') {
                $this->primary[] = $column['Field'];
                if ($column['Extra'] == 'auto_increment') {
                    $this->autoIncrementColumn = $cikumn['Field'];
                }
            }
        }
    }

    public function getColumns()
    {
        if (!$this->columns) $this->fetchColumns();
        return $this->columns;
    }

    public function getPrimaryKey()
    {
        if (!$this->columns) $this->fetchColumns();
        return $this->primary;
    }

    public function getAutoIncrementColumn()
    {
        if (!$this->columns) $this->fetchColumns();
        return $this->autoIncrementColumn;
    }
}
