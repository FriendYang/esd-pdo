<?php
/**
 * author:yyc
 * Date:2019/7/2
 * time:10:31
 **/

namespace ESDPDO\Pdo;
use ESD\Core\Channel\Channel;

class PdoPool
{
    /**
     * @var Channel
     */
    protected $pool;
    /**
     * @var PdoOneConfig
     */
    protected $pdoConfig;

    /**
     * RedisPool constructor.
     * @param PdoOneConfig $pdoConfig
     * @throws PdoException
     */
    public function __construct(PdoOneConfig $pdoConfig)
    {
        $this->pdoConfig = $pdoConfig;
        $config = $pdoConfig->buildConfig();
        $this->pool = DIGet(Channel::class, [$pdoConfig->getPoolMaxNumber()]);
        for ($i = 0; $i < $pdoConfig->getPoolMaxNumber(); $i++) {
            $db = new PdoDb($config);
            $this->pool->push($db);
        }
    }

    /**
     * @return PdoDb
     */
    public function db(): PdoDb
    {
        $db = getContextValue("Pdo:{$this->getPdoConfig()->getName()}");
        if ($db == null) {
            $db = $this->pool->pop();
            defer(function () use ($db) {
                $this->pool->push($db);
            });
            setContextValue("Pdo:{$this->getPdoConfig()->getName()}", $db);
        }
        return $db;
    }

    /**
     * @return PdoOneConfig
     */
    public function getPdoConfig(): PdoOneConfig
    {
        return $this->pdoConfig;
    }

    /**
     * @param PdoOneConfig $pdoConfig
     */
    public function setRedisConfig(PdoOneConfig $pdoConfig): void
    {
        $this->pdoConfig = $pdoConfig;
    }
}