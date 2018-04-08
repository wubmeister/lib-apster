<?php

namespace Apster\Db;

use Apster\Storage\ResultInterface;

class Row implements ResultInterface
{
    protected $table;
    protected $persistent;

    public function __construct(Table $table, bool $persistent = false)
    {
        $this->table = $table;
        $this->persistent = $persistent;
    }

    public function save()
    {
        $columns = $this->table->getColumns();
        $primary = $this->table->getPrimaryKey();
        $values = [];
        $autoInc = $this->table->getAutoIncrementColumn();

        foreach ($columns as $column) {
            if (isset($this->$column)) {
                $values[$column] = $this->$column;
            }
        }

        if ($this->persistent) {
            $where = [];
            foreach ($primary as $pri) {
                $where[$pri] = $values[$pri];
                unset($values[$pri]);
            }
            $this->table->update($values, $where);
        } else {
            $result = $this->table->insert($values);
            if ($result && $autoInc) {
                $this->$autoInc = $result;
            }
            $this->isPersistent = true;
        }
    }

    public function delete()
    {
        if ($this->persistent) {
            $primary = $this->table->getPrimaryKey();
            $where = [];
            foreach ($primary as $pri) {
                if (isset($this->$pri)) {
                    $where[$pri] = $this->$pri;
                }
            }
            $this->table->delete($where);
        }

    }

    public function isPersistent()
    {
        return $this->persistent;
    }
}
