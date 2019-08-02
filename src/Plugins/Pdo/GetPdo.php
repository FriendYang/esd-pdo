<?php
/**
 * author:yyc
 * Date:2019/7/2
 * time:11:01
 **/


namespace App\Plugins\Pdo;


trait GetPdo
{
    /**
     * @param string $name
     * @return mixed|\Redis
     * @throws RedisException
     */
    public function pdo($name = "default")
    {
        $db = getContextValue("Pdo:$name");
        if ($db == null) {
            /** @var PdoManyPool $pdoPool */
            $pdoPool = getDeepContextValueByClassName(PdoManyPool::class);
            $pool = $pdoPool->getPool($name);
            if ($pool == null) throw new PdoException("Pdo connection pool named {$name} was not found");
            return $pool->db();
        } else {
            return $db;
        }
    }
}