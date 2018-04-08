<?php

namespace Apster\Storage;

interface ResultInterface
{
    public function save();
    public function delete();
    public function isPersistent();
}
