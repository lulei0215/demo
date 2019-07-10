<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Controller;

class DemoController extends Controller
{
    public function add()
    {
        //设定商品数量
        $count   = 30;
        $listKey = "goods_list";
        //创建连接redis对象
        $redis = new Redis();
//        $redis->connect('127.0.0.1', 6379);
        for ($i = 1; $i <= $count; $i++) {
            //将商品id push到列表中
            $redis::rPush($listKey, $i);
        }
        return ['code' => 200, 'message' => 'OK'];
    }

    public function kill()
    {
        //假装是用户的唯一标识
        $uuid = md5(uniqid('user') . time());
        //创建连接redis对象
        $redis = new Redis();

        $listKey     = "goods_list";
        $orderKey    = "buy_order";
        $failUserNum = "fail_user_num";
        if ($goodsId = $redis::lPop($listKey)) {
            //秒杀成功
            //将幸运用户存在集合中
            $redis::hSet($orderKey, $goodsId, $uuid);
        } else {
            //秒杀失败
            //将失败用户计数
            $redis::incr($failUserNum);
        }
        echo "SUCCESS";
    }
}
