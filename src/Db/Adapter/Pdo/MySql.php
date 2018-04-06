<?php

namespace Apster\Db\Adapter\Pdo;

class MySql extends AbstractPdo
{
    protected $driver = 'mysql';
    protected $identifierQuote = '`';
}
