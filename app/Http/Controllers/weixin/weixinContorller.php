<?php

namespace App\Http\Controllers\weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use App\model\UserModel;

class weixinContorller extends Controller
{
    protected $appid = 'wxec28b3ff844e2bf3';
    protected $secret = '25bf2acbd494c6856754eb96580f21f1';

    /**
     * 接收微信服务器事件推送
     */
    public function wxEven()
    {
        $data = file_get_contents("php://input");
        $xml = simplexml_load_string($data);  //将xml 转换成对象格式
//        var_dump($xml);
        //记录日志
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/wx_event.log',$log_str,FILE_APPEND);

        $event = $xml->Event;      // 类型
        $openid = $xml->FromUserName;  //用户openid

        if(isset($xmk->MsgType)){  //检查变量是否设置
            if($xml->MsgType=='event'){  //消息类型

                if($event=='subscribe'){  //扫码关注事件
                    $sub_time = $xml->CreateTime;  //扫码关注时间
                    //获取用户信息
                    $user_info = $this->getUserInfo($openid);

                    //从数据库查看是否存在 保存用户信息到数据库
                    $u = UserModel::where(['openid'=>$openid])->first();
                    if(!$u){
                        $user_data = [
                            'openid'            => $openid,
                            'add_time'          => time(),
                            'nickname'          => $user_info['nickname'],
                            'sex'               => $user_info['sex'],
                            'headimgurl'        => $user_info['headimgurl'],
                            'subscribe_time'    => $sub_time,
                        ];
                        //保存到数据库
                        $id = UserModel::insertGetId($user_data);

//                    //Redis 缓存
//                    Redis::set('user',$user_data);
                    }


                    $msg = '欢迎关注信息';
                    $xml_response = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. $msg .']]></Content></xml>';
                    echo $xml_response;
                }

            }
        }


    }

    /**
     * 获取access_token
     */
    public function wxAccessToken()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->secret;
        $data = json_decode(file_get_contents($url,true));
        $token = $data->access_token;
        return $token;
    }

    /**
     * 获取用户基本信息
     * @param $openid
     * @return mixed
     */
    public function getUserInfo($openid)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->wxAccessToken().'&openid='.$openid.'&lang=zh_CN';
        $data = json_decode(file_get_contents($url,true));
        return $data;
    }

    /**
     * 展示用户列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userList()
    {
        $user = UserModel::paginate(2);
        $data = [
            'user'=>$user
        ];
        return view('weixin.user',$data);
    }

}

