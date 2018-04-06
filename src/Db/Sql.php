<?php

namespace Apster\Db;

use PDO;

class Sql
{
    const SELECT = 'SELECT';
    const INSERT = 'INSERT INTO';
    const INSERT_IGNORE = 'INSERT IGNORE INTO';
    const UPDATE = 'UPDATE';
    const DELETE = 'DELETE FROM';
    const FROM = 'FROM';
    const JOIN = 'JOIN';
    const SET = 'SET';
    const VALUES = 'VALUES';
    const WHERE = 'WHERE';
    const GROUP = 'GROUP BY';
    const HAVING = 'HAVING';
    const ORDER = 'ORDER BY';
    const LIMIT = 'LIMIT';
    const DUPLICATE = 'ON DUPLICATE KEY UPDATE';

    private static $order = [
        self::SELECT,
        self::INSERT, self::INSERT_IGNORE, self::UPDATE, self::DELETE,
        self::FROM, self::JOIN,
        self::SET, self::VALUES,
        self::WHERE,
        self::GROUP,
        self::HAVING,
        self::ORDER,
        self::LIMIT,
        self::DUPLICATE
    ];

    private static $func = [
        'select' => self::SELECT,
        'insert' => self::INSERT,
        'insertIgnore' => self::INSERT_IGNORE,
        'update' => self::UPDATE,
        'delete' => self::DELETE,
        'from' => self::FROM,
        'join' => self::JOIN,
        'set' => self::SET,
        'values' => self::VALUES,
        'where' => self::WHERE,
        'group' => self::GROUP,
        'having' => self::HAVING,
        'order' => self::ORDER,
        'limit' => self::LIMIT,
        'duplicate' => self::DUPLICATE
    ];

    protected $parts = [];
    protected $bindValues = [];
    protected $adapter;

    public static function factory()
    {
        return new Sql();
    }

    public function __construct(Adapter\AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function add($part, ...$params)
    {
        if (!isset($this->parts[$part])) {
            if ($part == self::VALUES) {
                $this->parts[$part] = [ [], [] ];
            } else {
                $this->parts[$part] = [];
            }
        }

        $string = null;

        if ($part == self::LIMIT) {
            $this->parts[$part] = [ $param[0] ];
            if (count($params) > 1) $this->parts[$part][] = $param[1];
        } else {
            if ($part == self::SELECT || $part == self::FROM || $part == self::JOIN) {
                $param = $part == self::JOIN ? $params[1] : $params[0];
                if (is_array($param)) {
                    foreach ($param as $alias => $name) {
                        $string = $this->adapter->quoteIdentifier($name);
                        if (!is_numeric($alias)) {
                            $string .= " AS " . $this->adapter->quoteIdentifier($alias);
                        }
                        $this->parts[$part][] = $string;
                    }
                    $string = null;
                } else {
                    $string = $param;
                }
            }
            else if ($part == self::WHERE || $part == self::HAVING) {
                if (is_array($params[0])) {
                    $string = '';
                    foreach ($params[0] as $key => $value) {
                        $string .= ($string ? ' AND ' : '') . $this->adapter->quoteIdentifier($key) . " = :{$key}";
                        $this->bind(":{$key}", $value);
                    }
                    $string = "({$string})";
                } else {
                    $string = "({$params[0]})";
                }

                $glue = count($params) > 1 ? strtoupper($params[1]) : 'AND';
                if (count($this->parts[$part]) > 0) {
                    $string = " {$glue} {$string}";
                }
            }
            else if ($part == self::VALUES) {
                if (is_array($params[0])) {
                    foreach ($params[0] as $key => $value) {
                        $this->parts[$part][0][] = $this->adapter->quoteIdentifier($key);
                        $this->parts[$part][1][] = ":{$key}";
                        $this->bind(":{$key}", $value);
                    }
                } else {
                    $key = $params[0];
                    $this->parts[$part][0][] = $this->adapter->quoteIdentifier($key);
                    $this->parts[$part][1][] = ":{$key}";
                }
            }
            elseif ($part == self::SET && is_array($params[0])) {
                foreach ($params[0] as $key => $value) {
                    $this->parts[$part][] = $this->adapter->quoteIdentifier($key) . " = :{$key}";
                    $this->bind(":{$key}", $value);
                }
            }
            else {
                if (is_array($params[0])) {
                    foreach ($params[0] as $value) {
                        $this->parts[$part][] = $value;
                    }
                } else {
                    $string = $params[0];
                }
            }

            if ($part == self::JOIN) {
                $this->parts[$part][] = [ strtoupper($params[0]), $string ];
            } else if ($string) {
                $this->parts[$part][] = $string;
            }
        }

        return $this;
    }

    public function bind($key, $value)
    {
        $this->bindValues[$key] = $value;
        return $this;
    }

    protected function construct($omitOrder = false)
    {
        $parts = [];

        foreach (self::$order as $part) {
            if ($omitOrder && $part == self::ORDER) continue;

            if (isset($this->parts[$part])) {
                if ($part == self::JOIN) {
                    $p = [];
                    foreach ($this->parts[$part] as $join) {
                        $p[] = $join[0] . ' JOIN ' . $join[1];
                    }
                    $parts[] = implode(' ', $p);
                } else if ($part == self::VALUES) {
                    $parts[] = '(' . implode(', ', $this->parts[$part][0]) . ') VALUES (' . implode(', ', $this->parts[$part][1]) . ')';
                } else {
                    $glue = ', ';
                    if ($part == self::WHERE || $part == self::HAVING) $glue = '';
                    $parts[] = $part . ' ' . implode($glue, $this->parts[$part]);
                }
            }
        }

        return implode(' ', $parts);
    }

    public function constructCount()
    {
        $sql = "SELECT COUNT(*) AS cnt FROM (" . $this->construct(true) . ") AS t";
        return $sql;
    }

    public function orWhere($where) {
        return $this->add(self::WHERE, $where, 'or');
    }

    public function orHaving($where) {
        return $this->add(self::HAVING, $where, 'or');
    }

    public function __call($name, $args)
    {
        if (isset(self::$func[$name])) {
            return $this->add(self::$func[$name], ...$args);
        }
    }

    public function __toString()
    {
        return $this->construct();
    }

    public function prepare()
    {
        $sql = $this->construct();
        $stmt = $this->adapter->prepare($sql);

        if (!$stmt) {
            $err = $this->adapter->errorInfo();
            throw new DatabaseException($err[2]);
        }

        foreach ($this->bindValues as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        return $stmt;
    }

    public function prepareCount()
    {
        $sql = $this->constructCount();
        $stmt = $this->adapter->prepare($sql);

        if (!$stmt) {
            $err = $this->adapter->errorInfo();
            throw new DatabaseException($err[2]);
        }

        foreach ($this->bindValues as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        return $stmt;
    }
}
