<?php

namespace Apster\Storage;

interface ResultInterface extends Iterator
{
    public function save();
    public function delete();
    public function isPersistent();
}
