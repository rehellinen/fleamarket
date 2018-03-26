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

// 关于自营商品
Route::get('api/:version/goods', 'api/:version.Goods/getGoods');
Route::get('api/:version/goods/:id', 'api/:version.Goods/getGoodsById', [], ['id'=>'\d+']);
Route::get('api/:version/goods/shop/:id', 'api/:version.Goods/getGoodsByShopId', [], ['id'=>'\d+']);
Route::get('api/:version/goods/recent/shop/:id', 'api/:version.Goods/getRecentGoodsByShopId', [], ['id'=>'\d+']);

// 关于二手商品
Route::get('api/:version/oldGoods', 'api/:version.old_goods/getOldGoods');
Route::get('api/:version/oldGoods/:id', 'api/:version.old_goods/getOldGoodsById', [], ['id'=>'\d+']);
Route::get('api/:version/oldGoods/seller/:id', 'api/:version.old_goods/getOldGoodsBySellerId', [], ['id'=>'\d+']);


// 关于Token
Route::post('api/:version/token/user', 'api/:version.Token/getToken');

// 关于用户
Route::put('api/:version/buyer', 'api/:version.Buyer/updateBuyerInfo');

// 关于自营商家
Route::get('api/:version/shop', 'api/:version.Shop/getNormalShop');
Route::get('api/:version/shop/:id', 'api/:version.Shop/getShopByID');

// 关于主题
Route::get('api/:version/theme', 'api/:version.Theme/getIndexNormalTheme');
