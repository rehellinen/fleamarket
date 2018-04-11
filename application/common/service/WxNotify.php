<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/4/3
 * Time: 10:45
 */

namespace app\common\service;

use app\common\model\Goods;
use enum\OrderEnum;
use think\Db;
use think\Exception;
use think\Loader;
use app\common\model\Order as OrderModel;
use app\common\service\Order as OrderService;
use think\Log;

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
                    if($order['status'] == 1){
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