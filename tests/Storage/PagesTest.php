<?php

namespace Test\Storage;

use Unittest\Testcase;
use Apster\Storage\Pages;

class PagesTest extends Testcase
{
    public function testCreate()
    {
        $pages = new Pages(42, 2, 10);

        $this->assertEquals(5, $pages->pageCount);
        $this->assertEquals(1, $pages->first);
        $this->assertEquals(10, $pages->firstItemNumber);
        $this->assertEquals(1, $pages->firstPageInRange);
        $this->assertEquals(2, $pages->current);
        $this->assertEquals(10, $pages->currentItemCount);
        $this->assertEquals(10, $pages->itemCountPerPage);
        $this->assertEquals(5, $pages->last);
        $this->assertEquals(19, $pages->lastItemNumber);
        $this->assertEquals(5, $pages->lastPageInRange);
        $this->assertEquals(3, $pages->next);
        $index = 0;
        for ($i = $pages->first; $i <= $pages->last; $i++) {
            $this->assertEquals($i, $pages->pagesInRange[$index]);
            $index++;
        }
        $this->assertEquals(1, $pages->previous);
        $this->assertEquals(42, $pages->totalItemCount);
    }
}
