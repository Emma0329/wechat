<?php
 
ini_set('display_errors', 'on');
 
if(strpos(strtolower(PHP_OS), 'win') === 0)
 
{
 
    exit("start.php not support windows.\n");
 
}
 
// 检查扩展
 
if(!extension_loaded('pcntl'))
 
{
 
    exit("Please install pcntl extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
 
}
 
if(!extension_loaded('posix'))
 
{
 
    exit("Please install posix extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
 
}
 
define('APP_PATH', __DIR__ . '/application/');//如果修改了也要跟着修改，tp的application
 
define('BIND_MODULE','push/Run');//这个位置是你唯一要自定义的
 
// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';