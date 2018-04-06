<?php

use Storage\Pages;
use Storage\ResultsetInterface;

class MockResultset implements ResultsetInterface
{
    protected $pages;
    protected $data;
    protected $it;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function paginate($page, $limit = 10)
    {
        $this->pages = new Pages(42, $page, $limit);
    }

    public function getPages()
    {
        return $this->pages;
    }

    public function current()
    {
        return $this->data[$this->it];
    }

    public function key()
    {
        return $this->it;
    }

    public function rewind()
    {
        $this->it = 0;
    }

    public function next()
    {
        $this->it++;
    }

    public function valid()
    {
        return $this->it < count($this->data);
    }
}
