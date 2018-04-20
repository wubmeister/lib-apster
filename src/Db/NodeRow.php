<?php

namespace Apster\Db;

use Apster\Storage\ResultInterface;
use Apster\Tree\Node;

class NodeRow extends Node implements ResultInterface
{
    use RowTrait;

    public function __construct(Table $table, bool $persistent = false)
    {
        $this->table = $table;
        $this->persistent = $persistent;
    }

    public function saveDeep()
    {
        $this->save();
        foreach ($this as $child) {
            $child->save();
        }
    }
}
