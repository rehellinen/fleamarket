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
Route::get('api/:version/newGoods', 'api/:version.Goods/getNewGoods');
Route::get('api/:version/newGoods/:id', 'api/:version.Goods/getNewGoodsById', [], ['id'=>'\d+']);
Route::get('api/:version/newGoods/shop/:id', 'api/:version.Goods/getNewGoodsByShopId', [], ['id'=>'\d+']);
Route::get('api/:version/newGoods/recent/shop/:id', 'api/:version.Goods/getRecentNewGoodsByShopId', [], ['id'=>'\d+']);

// 关于二手商品
Route::get('api/:version/oldGoods', 'api/:version.Goods/getOldGoods');
Route::get('api/:version/oldGoods/:id', 'api/:version.Goods/getOldGoodsById', [], ['id'=>'\d+']);
Route::get('api/:version/oldGoods/seller/:id', 'api/:version.Goods/getOldGoodsBySellerId', [], ['id'=>'\d+']);
Route::get('api/:version/oldGoods/category/:id', 'api/:version.Goods/getOldGoodsByCategoryId', [], ['id'=>'\d+']);


// 关于Token
Route::post('api/:version/token/buyer', 'api/:version.Token/getToken');
Route::post('api/:version/token/verify', 'api/:version.Token/verifyToken');

// 关于买家
Route::put('api/:version/buyer', 'api/:version.Buyer/updateBuyerInfo');
Route::get('api/:version/buyer', 'api/:version.Buyer/getBuyerInfo');

// 关于自营商家
Route::get('api/:version/shop', 'api/:version.Shop/getNormalShop');
Route::get('api/:version/shop/:id', 'api/:version.Shop/getShopByID');

// 关于主题
Route::get('api/:version/theme', 'api/:version.Theme/getIndexNormalTheme');
Route::get('api/:version/category/:id', 'api/:version.Theme/getThemeCategory');

// 关于订单
Route::post('api/:version/order', 'api/:version.Order/placeOrder');
Route::post('api/:version/preOrder', 'api/:version.Pay/getPreOrder');
