<?php
/**
 * author:yyc
 * Date:2019/7/2
 * time:11:24
 **/


namespace App\Plugins\Pdo;
use PDO;

class PdoManyPool
{
    protected $poolList = [];

    /**
     * 获取连接池
     * @param $name
     * @return PdoPool|null
     */
    public function getPool($name = "default")
    {
        return $this->poolList[$name] ?? null;
    }

    /**
     * 添加连接池
     * @param PdoPool $pdoPool
     */
    public function addPool(PdoPool $pdoPool)
    {
        $this->poolList[$pdoPool->getpdoConfig()->getName()] = $pdoPool;
    }

    /**
     * @return Pdo
     * @throws PdoException
     */
    public function db(): PDO
    {
        $default = $this->getPool();
        if ($default == null) {
            throw new PdoException("没有设置默认的pdo");
        }
        return $default->db();
    }
}