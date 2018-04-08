<?php

use Apster\Db\Rowset;

class MockRowset extends Rowset
{
    public function paginate($page, $limit = 10)
    {
        $count = 20;
        $this->pages = new Pages($count, $page, $limit);
    }

    public function getPages()
    {
        return $this->pages;
    }

    public function current()
    {
        if (!$this->rows) $this->rewind();
        return $this->rows[$index];
    }

    public function rewind()
    {
        $this->index = 0;
        if (!$this->rows) {
            $this->rows = [];
            for ($i = 0; $i < 20; $i++) {
                $this->rows[] = new MockRow($this->table, true);
            }
        }
    }

    public function next()
    {
        $this->index++;
    }

    public function valid()
    {
        return $this->index < count($this->rows);
    }
}
