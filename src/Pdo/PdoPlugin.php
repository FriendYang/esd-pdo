<?php
/**
 * author:yyc
 * Date:2019/7/2
 * time:10:36
 **/


namespace ESD\Plugins\Pdo;

use ESD\Core\Context\Context;
use ESD\Core\PlugIn\AbstractPlugin;
use ESD\Core\Plugins\Logger\GetLogger;
use ESD\Core\Server\Server;


class PdoPlugin extends AbstractPlugin
{
    use GetLogger;
    /**
     * @var pdoConfig
     */
    protected $pdoConfig;
    public function __construct()
    {
        parent::__construct();
        $this->pdoConfig = new PdoConfig();
        $this->pdoConfig->setPdoConfigs([]);
    }
    /**
     * 获取插件名字
     * @return string
     */
    public function getName(): string
    {
        return "Pdo";
    }

    /**
     * 在服务启动前
     * @param Context $context
     * @return mixed
     * @throws \ESD\Core\Plugins\Config\ConfigException
     * @throws \Exception
     */
    public function beforeServerStart(Context $context)
    {
        //所有配置合併
        foreach ($this->pdoConfig->getPdoConfigs() as $config) {
            $config->merge();
        }

        $configs = Server::$instance->getConfigContext()->get(PdoOneConfig::key, []);
        foreach ($configs as $key => $value) {
            $pdoOneConfig = new PdoOneConfig("","","");
            $pdoOneConfig->setName($key);
            $this->pdoConfig->addPdoOneConfig($pdoOneConfig->buildFromConfig($value));
        }
        $pdoProxy = new PdoProxy();
        $this->setToDIContainer(\PDO::class, $pdoProxy);
        $this->setToDIContainer(Pdo::class, $pdoProxy);
        $this->setToDIContainer(PdoConfig::class, $this->pdoConfig);
        return;
    }

    /**
     * 在进程启动前
     * @param Context $context
     * @throws PdoException
     */
    public function beforeProcessStart(Context $context)
    {

        $pdoManyPool = new PdoManyPool();
        if (empty($this->pdoConfig->getPdoConfigs())) {
            $this->warn("没有pdo配置");
        }
        foreach ($this->pdoConfig->getPdoConfigs() as $key => $value) {
            $pdoPool = new PdoPool($value);
            $pdoManyPool->addPool($pdoPool);
            $this->debug("已添加名为 {$value->getName()} 的pdo连接池");
        }
        $context->add("pdoPool", $pdoManyPool);
        $this->setToDIContainer(PdoManyPool::class, $pdoManyPool);
        $this->setToDIContainer(PdoPool::class, $pdoManyPool->getPool());
        $this->ready();
    }


    /**
     * @return PdoOneConfig[]
     */
    public function getConfigList(): array
    {
        return $this->PdoConfig->getPdoConfigs();
    }

    /**
     * @param PdoOneConfig[] $configList
     */
    public function setConfigList(array $configList): void
    {
        $this->redisConfig->setPdoConfigs($configList);
    }

    /**
     * @param PdoOneConfig $PdoOneConfig
     */
    public function addConfigList(PdoOneConfig $pdoOneConfig): void
    {
        $this->redisConfig->addRedisOneConfig($pdoOneConfig);
    }

}