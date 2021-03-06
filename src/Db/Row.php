<?php

namespace Apster\Db;

use Apster\Storage\ResultInterface;

class Row implements ResultInterface
{
    use RowTrait;

    public function __construct(Table $table, bool $persistent = false)
    {
        $this->table = $table;
        $this->persistent = $persistent;
    }
}
