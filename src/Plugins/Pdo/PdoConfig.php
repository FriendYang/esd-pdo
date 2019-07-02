<?php
/**
 * author:yyc
 * Date:2019/7/2
 * time:10:37
 **/


namespace ESDPDO\Pdo;


class PdoConfig
{
    /**
     * @var PdoOneConfig[]
     */
    protected $pdoConfigs;

    /**
     * @return RedisOneConfig[]
     */
    public function getPdoConfigs(): array
    {

        return $this->pdoConfigs;
    }

    /**
     * @param RedisOneConfig[] $redisConfigs
     */
    public function setPdoConfigs(array $pdoConfigs): void
    {
        $this->pdoConfigs = $pdoConfigs;
    }

    public function addPdoOneConfig(PdoOneConfig $buildFromConfig)
    {
        $this->pdoConfigs[$buildFromConfig->getName()] = $buildFromConfig;

    }
}