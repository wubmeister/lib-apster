<?php

namespace Apster\Db\Adapter\Pdo;

class Mysql extends AbstractPdo
{
    protected $driver = 'mysql';
    protected $identifierQuote = '`';
}
