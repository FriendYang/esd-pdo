<?php
/**
 * author:yyc
 * Date:2019/7/2
 * time:10:42
 **/


namespace App\Plugins\Pdo;

use ESD\Core\Plugins\Config\BaseConfig;
class PdoOneConfig extends BaseConfig
{
    const key = "pdo";
    /**
     * @var string
     */
    protected $dsn;
    /**
     * @var string
     */
    protected $user;
    /**
     * @var string
     */
    protected $password;
    /**
     * @var array
     */
    protected $options;
    /**
     * @var string
     */
    protected $name;

    /**
     * PdoOneConfig constructor.
     * @param string $dsn
     * @param string $user
     * @param string $password
     * @param []|null $options
     * @param string $name
     * @param int $poolMaxNumber
     * @throws \ReflectionException
     */
    public function __construct(string $dsn, string $user, string $password,array $options = [], string $name = "default", int $poolMaxNumber = 10)
    {
        parent::__construct(self::key, true, "name");
        $this->name = $name;
        $this->poolMaxNumber = $poolMaxNumber;
        $this->dsn = $dsn;
        $this->user = $user;
        $this->password = $password;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getPoolMaxNumber(): int
    {
        return $this->poolMaxNumber;
    }

    /**
     * @param int $poolMaxNumber
     */
    public function setPoolMaxNumber(int $poolMaxNumber): void
    {
        $this->poolMaxNumber = $poolMaxNumber;
    }


    /**
     * @return string
     */
    public function getDsn(): string
    {
        return $this->dsn;
    }


    /**
     * @param string $dsn
     */
    public function setDsn(string $dsn): void
    {
       $this->dsn = $dsn;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser(string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
    /**
     * @return array
     */
    public function getOptions(): string
    {
        return $this->password;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    /**
     * 构建配置
     * @throws RedisException
     */
    public function buildConfig()
    {
        if (!extension_loaded('PDO')) {
            throw new RedisException("Lack of pdo expansion");
        }
        if ($this->poolMaxNumber < 1) {
            throw new RedisException("PoolMaxNumber must be greater than 1");
        }
        if (empty($this->dsn)) {
            throw new PdoException("dsn must be set");
        }
        if (empty($this->user)) {
            throw new PdoException("user must be set");
        }
        if (empty($this->password)) {
            throw new PdoException("password must be set");
        }
        return [
            'dsn' => $this->dsn,
            'user' => $this->user,
            'password' => $this->password,
            'options' => $this->options
        ];
    }
}