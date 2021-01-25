<?php
// +----------------------------------------------------------------------
// | LikeShop100%开源免费商用电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | Gitee下载：https://gitee.com/likemarket/likeshopv2
// | 访问官网：https://www.likemarket.net
// | 访问社区：https://home.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 微信公众号：好象科技
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------

// | Author: LikeShopTeam
// +----------------------------------------------------------------------
namespace app\api\logic;
use app\common\logic\LogicBase;
use app\common\logic\QrCodeLogic;
use app\common\model\Client_;
use app\common\server\UrlServer;
use think\Db;

class ShareLogic extends LogicBase {
    public static function shareGoods($user_id,$goods_id,$url,$client){
        $qr_code_logic = new QrCodeLogic();
        $goods = Db::name('goods')->where(['id'=>$goods_id])->find();
        $base64 = '';
        if($goods){
            $user = Db::name('user')->where(['id'=>$user_id])->find();
            switch ($client){
                case Client_::mnp: //小程序
                    $url_type = 'path';
                    break;
                case Client_::h5: //H5.
                    $url_type = 'url';
                    $url = url($url,['id'=>$goods_id,'invite_code'=>$user['distribution_code']],'',true);
                    break;
                case Client_::android:
                case Client_::ios:
                    $url_type = 'url';
                    $url = url($url,['id'=>$goods_id,'invite_code'=>$user['distribution_code'],'isapp'=>1],'',true);
            }
            $base64 = $qr_code_logic->makeGoodsPoster($user,$goods,$url,$url_type);
        }
        return $base64;
    }

}