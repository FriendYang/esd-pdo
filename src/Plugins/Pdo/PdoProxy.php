<?php
/**
 * author:yyc
 * Date:2019/7/2
 * time:11:00
 **/


namespace ESDPDO\Pdo;


class PdoProxy
{
    use GetPdo;

    /**
     * @param $name
     * @return mixed
     * @throws RedisException
     */
    public function __get($name)
    {
        return $this->pdo()->$name;
    }

    /**
     * @param $name
     * @param $value
     * @throws RedisException
     */
    public function __set($name, $value)
    {
        $this->pdo()->$name = $value;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws RedisException
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->pdo(), $name], $arguments);
    }
}