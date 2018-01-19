<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/11/23
 * Time: 0:12
 */

namespace app\common\model;


class Banner extends Base
{
    public function getBanners()
    {
        $condition = [
            'status' => 1
        ];
        $maxCount = config('admin.max_banner_count');
        return $this->where($condition)->order('listorder desc, id desc')
                ->limit($maxCount)->select()->toArray();
    }
}