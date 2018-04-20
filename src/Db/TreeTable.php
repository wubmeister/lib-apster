<?php

namespace Apster\Db;

class TreeTable extends Table
{
    protected $leftKey = 'left';
    protected $rightKey = 'right';

    protected function resolveChildId(&$child)
    {
        if (is_array($child)) {
            return $this->insert($child);
        }

        return $child;
    }

    public function appendChild($child, int $parentId)
    {
        $parent = $this->findOneById($parentId);
        if (!$parent) {
            throw new Exception("Cannot find parent node");
        }

        $childId = $this->resolveChildId($child);

        // Do some tree manipulation
        $rightKey = $this->rightKey;
        $this->shift("right >= {$parent->$rightKey}", $child->size(), SHIFT_RIGHT);
        $this->shift("left >= {$parent->$rightKey}", $child->size(), SHIFT_LEFT);
        $child->realign($parent->$rightKey);
        $child->saveDeep();
    }

    public function insertBefore($child, int $refId)
    {
        $reference = $this->findOneById($refId);
        if (!$reference) {
            throw new Exception("Cannot find reference node");
        }

        $childId = $this->resolveChildId($child);

        // Do some tree manipulation
        $rightKey = $this->rightKey;
        $this->shift("left >= {$reference->$leftKey}", $child->size(), SHIFT_BOTH);
        $child->realign($reference->$leftKey);
        $child->saveDeep();
    }

    public function replaceChild($child, int $refId)
    {
        $reference = $this->findOneById($refId);
        if (!$reference) {
            throw new Exception("Cannot find reference node");
        }

        $childId = $this->resolveChildId($child);
        // Do some tree manipulation
    }

    public function removeChild(int $oldId)
    {
        $child = $this->findOneById($oldId);
        if (!$child) {
            throw new Exception("Cannot find child node");
        }

        // Do some tree manipulation
    }
}
