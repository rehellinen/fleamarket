<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

// 以下为API的路由

// 关于Banner
Route::get('api/:version/banner', 'api/:version.Banner/getBanner');

// 关于商品
Route::get('api/:version/goods', 'api/:version.Goods/getGoods');
Route::get('api/:version/goods/:id', 'api/:version.Goods/getGoodsById');
