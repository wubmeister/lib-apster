<?php

use Apster\Db\Row;

class MockRow extends Row
{
    public function __construct(Table $table, bool $persistent)
    {
        parent::__construct($table, $persistent);

        $this->id = 1;
        $this->name = 'Dummy';
    }
}
