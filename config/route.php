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

// 所有商品中查找
Route::get('api/:version/goods/check', 'api/:version.Goods/checkInfo');
Route::get('api/:version/goods/category/:id', 'api/:version.Goods/getGoodsByCategoryId', [], ['id'=>'\d+']);
Route::get('api/:version/goods/downed', 'api/:version.Goods/getDownedGoods');
Route::post('api/:version/goods', 'api/:version.Goods/addGoods');
Route::post('api/:version/goods/status', 'api/:version.Goods/updateGoodsStatus');
Route::put('api/:version/goods', 'api/:version.Goods/editGoods');
// 自营商品中查找
Route::get('api/:version/newGoods', 'api/:version.Goods/getGoods?type=1');
Route::get('api/:version/newGoods/index', 'api/:version.Goods/getIndexGoods?type=1');
Route::get('api/:version/newGoods/:id', 'api/:version.Goods/getGoodsById?type=1', [], ['id'=>'\d+']);
Route::get('api/:version/newGoods/shop/:id', 'api/:version.Goods/getGoodsByForeignId?type=1', [], ['id'=>'\d+']);
Route::get('api/:version/newGoods/recent/shop/:id', 'api/:version.Goods/getRecentNewGoodsByShopId', [], ['id'=>'\d+']);

// 二手商品中查找
Route::get('api/:version/oldGoods', 'api/:version.Goods/getGoods?type=2');
Route::get('api/:version/oldGoods/index', 'api/:version.Goods/getIndexGoods?type=2');
Route::get('api/:version/oldGoods/:id', 'api/:version.Goods/getGoodsById?type=2', [], ['id'=>'\d+']);
Route::get('api/:version/oldGoods/seller/:id', 'api/:version.Goods/getGoodsByForeignId?type=2', [], ['id'=>'\d+']);
// API审查到这里

// 关于主题
Route::get('api/:version/theme', 'api/:version.Theme/getIndexNormalTheme');
Route::get('api/:version/category/:id', 'api/:version.Theme/getThemeCategory');

// 关于Token
Route::post('api/:version/token/buyer', 'api/:version.Token/getBuyerToken');
Route::post('api/:version/token/seller', 'api/:version.Token/getSellerToken');
Route::post('api/:version/token/verify', 'api/:version.Token/verifyToken');
Route::post('api/:version/token/openID', 'api/:version.Token/verifyOpenID');

// 关于买家
Route::put('api/:version/buyer', 'api/:version.Buyer/updateBuyerInfo');
Route::get('api/:version/buyer', 'api/:version.Buyer/getBuyerInfo');

// 关于自营商家
Route::get('api/:version/shop', 'api/:version.Shop/getNormalShop');
Route::get('api/:version/shop/:id', 'api/:version.Shop/getShopByID');
Route::post('api/:version/shop', 'api/:version.Shop/addOrEditShop');

// 关于二手卖家
Route::get('api/:version/seller/:id', 'api/:version.Seller/getSellerByID');
Route::post('api/:version/seller', 'api/:version.Seller/addOrEditSeller');

// 关于订单
Route::get('api/:version/order/:status', 'api/:version.Order/getOrder', [], ['status'=>'[-]*\d+']);
Route::get('api/:version/order/:id/:type', 'api/:version.Order/getDetail', [], ['id'=>'\d+']);
Route::get('api/:version/order/price', 'api/:version.Order/getTotalPrice');
Route::post('api/:version/order', 'api/:version.Order/placeOrder');
Route::post('api/:version/order/deliver/:id', 'api/:version.Order/deliver', [], ['id'=>'\d+']);
Route::post('api/:version/order/confirm/:id', 'api/:version.Order/confirm', [], ['id'=>'\d+']);
Route::post('api/:version/order/withdraw/:id', 'api/:version.Order/withdraw');
Route::delete('api/:version/order/:id', 'api/:version.Order/deleteOrder');

// 关于支付
Route::post('api/:version/preOrder', 'api/:version.Pay/getPreOrder');
Route::post('api/:version/notify', 'api/:version.Pay/receiveNotify');

// 关于图片
Route::post('api/:version/image', 'api/:version.Image/appUpload');
Route::post('api/:version/image/goods', 'api/:version.Image/imageUpload');

// 邮件
Route::get('api/:version/email', 'api/:version.Emailer/send');

// 微信相关
Route::get('api/:version/qr', 'api/:version.Weixin/getQRCode');

// 关于电费
Route::get('api/:version/electricity/three', 'api/:version.Electricity/getThreeDays');
Route::get('api/:version/electricity/surplus', 'api/:version.Electricity/getSurplus');