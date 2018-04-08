<?php

namespace Apster\Storage;

use Iterator;

interface ResultsetInterface extends Iterator
{
    public function paginate($page, $limit = 10);
    public function getPages();
}
