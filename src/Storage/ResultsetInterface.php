<?php

namespace Apster\Storage;

interface ResultsetInterface extends Iterator
{
    public function paginate($page, $limit = 10);
    public function getPages();
}
