<?php

namespace Apster\Storage;

class Pages
{
    public function construct(int $count, int $page, int $limit)
    {
        $this->pageCount = ceil($count / $limit);
        $this->first = 1;
        $this->firstItemNumber = ($page - $this->first) * $limit;
        $this->firstPageInRange = $this->first;
        $this->current = $page;
        $this->currentItemCount = $page == $this->pageCount ? $count - $firstItemNumber : $limit;
        $this->itemCountPerPage = $limit;
        $this->last = $this->pageCount;
        $this->lastItemNumber = $this->firstItemNumber + $this->currentItemCount;
        $this->lastPageInRange = $this->last;
        $this->next = $page < $this->pageCount ? $page + 1 : null;
        $this->pagesInRange = [];
        for ($i = $this->first; $i <= $this->last; $i++) {
            $this->pagesInRange[] = $i;
        }
        $this->previous = $page > 1 ? $page - 1 : null;
        $this->totalItemCount = $count;
    }
}
