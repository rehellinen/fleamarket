<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 2018/1/27
 * Time: 23:37
 */

namespace app\common\service;


use app\common\exception\SellerException;
use app\common\exception\TokenException;
use app\common\model\Seller;
use enum\ScopeEnum;

class SellerToken extends Token
{
    private $tele;
    private $password;

    public function __construct($tele, $password)
    {
        $this->tele = $tele;
        $this->password = $password;
    }

    public function get()
    {
        // 验证用户名、密码是否正确
        $sellerModel = new Seller();
        $seller = $sellerModel->getSellerByTele($this->tele);
        if(!$seller){
            throw new SellerException();
        }
        self::checkPassword($this->password, $seller);

        // 生成Token
        $token = $this->grantToken($seller);
        return $token;
    }

    private function grantToken($seller)
    {
        $key = self::generateToken();
        $value = $this->prepareCachedValue($seller);

        $token = $this->saveToCache($key, $value);

        return $token;
    }

    private function prepareCachedValue($seller)
    {
        $isRoot = $seller->is_root;
        if($isRoot){
            $scope = ScopeEnum::Super;
        }else{
            $scope = ScopeEnum::Seller;
        }
        $array = [
            'sellerID' => $seller->id,
            'tele' => $seller->tele,
            'scope' => $scope
        ];
        return json_encode($array);
    }

    private function saveToCache($key, $value)
    {
        $expireIn = config('admin.token_expire_in');

        $request = cache($key, $value, $expireIn);

        if(!$request){
            throw new TokenException([
                'status' => 10005,
                'message' => '服务器缓存异常'
            ]);
        }
        return $key;
    }
}