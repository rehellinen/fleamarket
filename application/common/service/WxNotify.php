<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/4/3
 * Time: 10:45
 */

namespace app\common\service;

use app\common\model\Goods;
use app\common\model\Seller;
use app\common\model\Shop;
use enum\OrderEnum;
use enum\StatusEnum;
use enum\TypeEnum;
use think\Db;
use think\Exception;
use think\Loader;
use app\common\model\Order as OrderModel;
use app\common\service\Order as OrderService;
use think\Log;
use phpmailer\EmailTo;

Loader::import('pay.WxPay', EXTEND_PATH, '.Api.php');

class WxNotify extends \WxPayNotify
{
    private $orderID;
    private $orderNO;

    public function NotifyProcess($data, &$msg)
    {
        if($data['result_code'] == 'SUCCESS'){
            // 支付成功
            $orderIdentify = $data['out_trade_no'];
            if(is_numeric($orderIdentify)){
                $this->orderID = $orderIdentify;
            }else{
                $this->orderNO = $orderIdentify;
            }

            Db::startTrans();
            try{
                if($this->orderID){
                    $orders = (new OrderModel)->where('id', '=', $this->orderID)->select()->toArray();
                }else{
                    $orders = (new OrderModel)->where('order_no', '=', $this->orderNO)->select()->toArray();
                }

                foreach ($orders as $order){
                    // 发送邮件
//                    if($order['type'] == TypeEnum::NewGoods){
//                        $user = (new Shop())->where(['id' => $order['foreign_id']])->find();
//                    }else{
//                        $user = (new Seller())->where(['id' => $order['foreign_id']])->find();
//                    }
//                    EmailTo::send('912377791@qq.com', '有新订单', '请登录小程序查看详情');
                    if($order['status'] == OrderEnum::UNPAID){
                        $orderService = new OrderService();
                        $stockStatus = $orderService->checkStock([$order['id']]);

                        if($stockStatus['pass']){
                            $this->updateOrderStatus($order['id'], true);
                            $this->reduceStock($stockStatus['singleOrder'][0]);
                        }else{
                            $this->updateOrderStatus($order['id'], false);
                        }
                    }
                }
                Db::commit();
                return true;
            }catch (Exception $e){
                Db::rollback();
                Log::error($e);
                return false;
            }
        }else{
            // 支付失败
            return true;
        }
    }

    private function updateOrderStatus($orderID, $success)
    {
        $status = $success ? OrderEnum::PAID : OrderEnum::PAID_BUT_NO_GOODS;
        (new OrderModel())->where('id='.$orderID)->update([
            'status' => $status
        ]);
    }

    private function reduceStock($stockStatus)
    {
        foreach ($stockStatus['goodsStatusArray'] as $singleGoods)
        {
            (new Goods)->where('id='.$singleGoods['goods_id'])->setDec('quantity', $singleGoods['count']);
        }
    }
}