<?php

use Storage\Pages;
use Storage\ResultInterface;

class MockResult implements ResultInterface
{
    public function save(){}
    public function delete(){}

    public function isPersistent()
    {
        return true;
    }
}
