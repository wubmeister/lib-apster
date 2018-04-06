<?php

namespace Apster\Db;

use PDO;
use Apster\Storage\Pages;
use Apster\Storage\ResultsetInterface;

class Rowset implements ResultsetInterface
{
    protected $sql;
    protected $table;
    protected $stmt;
    protected $rows;
    protected $index;
    protected $pages;

    public function __construct(Sql $sql, Table $table)
    {
        $this->sql = $sql;
        $this->table = $table;
    }

    public function paginate($page, $limit = 10)
    {
        $countStmt = $this->sql->prepareCount($this->table->getPdo());
        $countStmt->execute();
        $row = $countStmt->fetch(PDO::FETCH_NUM);
        $count = $row ? (int)$row[0] : 0;

        $this->pages = new Pages($count, $page, $limit);

        $this->sql->limit(($page - 1) * $limit, $limit);
    }

    public function getPages()
    {
        return $this->pages;
    }

    public function current()
    {
        if (!$this->stmt && !$this->rows) $this->rewind();
        return $this->rows[$index];
    }

    public function key()
    {
        return $this->index;
    }

    public function rewind()
    {
        $this->index = 0;
        if (!$this->stmt) {
            $this->stmt = $this->sql->prepare($this->table->getPdo());
            $this->stmt->setFetchMode(PDO::FETCH_CLASS, $this->table->rowClass, [ $this->table, true ]);
            $this->rows = [];
            if ($row = $this->stmt->fetch()) {
                $this->rows[] = $row;
            }
        }
    }

    public function next()
    {
        $this->index++;
        if ($this->index >= count($this->rows)) {
            if ($row = $this->stmt->fetch()) {
                $this->rows[] = $row;
            }
        }
    }

    public function valid()
    {
        return $this->index < count($this->rows);
    }
}
