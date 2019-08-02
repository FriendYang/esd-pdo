<?php
/**
 * author:yyc
 * Date:2019/7/2
 * time:11:10
 **/


namespace App\Plugins\Pdo;

use ESD\Psr\DB\DBInterface;
use PDO;
use PDOException;
use App\Plugins\Pdo\PdoException as PDE;
class PdoDb implements DBInterface
{
    /**
     * @var \PDO
     */
    private $_pdo;

    private $_lastQuery;

    public function __construct($pdoConfig)
    {
         $this->connect($pdoConfig);
    }

    public function connect(array $pdoConfig): void
    {
        if ($this->_pdo) {
            return;
        }
        try {
//            $this->_pdo = new PDO($pdoConfig['dsn'], $pdoConfig['user'], $pdoConfig['password'], $pdoConfig['options']);
            //$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $obj = new PDO($pdoConfig['dsn'], $pdoConfig['user'], $pdoConfig['password'], $pdoConfig['options']);
            $obj->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $obj = $this->_pdo;
        } catch (PDOException $e) {
            throw new PDE($e->getMessage());
        }

    }


    public function __call($name, $arguments)
    {
        $this->_lastQuery = $arguments;
        return $this->execute($name, function () use ($name, $arguments) {
            return call_user_func_array([$this->_pdo, $name], $arguments);
        });
    }

    public function __set($name, $value)
    {
        $this->_pdo->$name = $value;
    }

    public function __get($name)
    {
        return $this->_pdo->$name;
    }

    public function getType()
    {
        return "Pdo";
    }

    public function execute($name,callable $call = null)
    {
        if ($call != null) {
            return $call();
        }
    }

    public function getLastQuery()
    {
        return $this->_lastQuery;
    }
}