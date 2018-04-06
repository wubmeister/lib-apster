<?php

namespace Apster\Db\Adapter\Pdo;

use PDO;
use Apster\Db\Adapter\AdapterInterface;

class AbstractPdo extends PDO implements AdapterInterface
{
    protected $driver = 'generic';
    protected $identifierQuote = '"';

    public function __construct($options) {
        $dsn = $this->driver . ':';
        $optionPairs = [];
        $user = null;
        $password = null;

        foreach ($options as $key => $value) {
            if ($key == 'username') $user = $value;
            else if ($key == 'password') $password = $value;
            else $optionPairs[] = "{$key}={$value}";
        }
        $dsn .= implode(';', $optionPairs);

        parent::__construct($dsn, $user, $password);
    }

    public function quoteIdentifier(string $identifier)
    {
        // TODO recognize identifiers in function calls, but that will require some form of lexical analysis
        if (preg_match('/^([a-zA-Z0-9_]+)(\.([a-zA-Z0-9_]+))?$/', trim($identifier), $match)) {
            $iq = $this->identifierQuote;
            return $iq . $match[1] . (count($match) > 2 ? $iq . '.' . $iq . $match[3] : '') . $iq;
        }
        return $identifier;
    }
}
