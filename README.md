# esd-pdo  
**pdo 连接池**  
基于esd框架使用，返回 pdo 链接对象  
使用案例：

    use App\Plugins\Pdo\GetPdo;
    class Index extends Base
    {
      use GetPdo;
          public function index3(){
            $rs = $this->pdo()->query("SELECT * FROM testdb..testtable");
            $row = $rs->fetchAll(\PDO::FETCH_ASSOC);
            var_dump($row);
            return $this->blade->render("app::wss");
          }
    }
配置文件：  

    pdo:
     default:
        dsn: 'odbc:testdsn'
        user: 'test'
        password: '123456'
        
插件加载： 在 Application 引入PdoPlugin
    
    $goApp = new GoApplication();
    $goApp->addPlug(new PdoPlugin());
    $goApp->run(Application::class);

