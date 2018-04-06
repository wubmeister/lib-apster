<?php

namespace Apster\Db;

use Apster\Storage\ResultsetInterface;

class Row implements ResultsetInterface
{
    protected $table;
    protected $persistent;

    public function __construct(Table $table, bool $persistent)
    {
        $this->table = $table;
        $this->persistent = $persistent;
    }

    public function save()
    {
        $columns = $this->table->getColumns();
        $primary = $this->table->getPrimaryKey();
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
            $this->table->insert($values);
        }
    }

    public function delete()
    {
        if ($this->persistent) {
            $columns = $this->table->getColumns();
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
