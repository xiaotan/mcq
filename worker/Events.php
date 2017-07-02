<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{

    /**
     * 新建一个类的静态成员，用来保存数据库实例
     */
    public static $db = null;

    /**
     * 进程启动后初始化数据库连接
     */
    public static function onWorkerStart($worker)
    {
        self::$db = new Workerman\MySQL\Connection('127.0.0.1', '3306', 'root', 'root', 'mcq');
    }

    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     * 
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {
        /*// 向当前client_id发送数据 
        Gateway::sendToClient($client_id, "Hello $client_id\r\n");
        // 向所有人发送
        Gateway::sendToAll("$client_id login\r\n");*/
        Gateway::sendToClient($client_id, json_encode(array(
            'type'      => 'init',
            'client_id' => $client_id
        )));
    }

    /*public static function onMessage($client_id, $msg)
    {
        $msg = json_decode($msg, true);
        switch($msg['type'])
        {
            case 'login':
                // 记录session，表明认证成功
                $_SESSION['login'] = true;
                break;
            // 30秒后客户端发来心跳回复时，仍然没认证，则关闭连接
            case 'pong':
                if(empty($_SESSION['login']))
                {
                     Gateway::closeClient($client_id);
                }
        }
    }*/

    /**
     * 当用户断开连接时触发的方法
     * @param integer $client_id 断开连接的客户端client_id
     * @return void
     */
    public static function onClose($client_id)
    {
        self::$db->query("UPDATE `mc_pet_member` SET `online` = 0 WHERE client_id='".$client_id."'");
    }
}
