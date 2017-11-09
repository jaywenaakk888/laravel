<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use App\jobs\SendActivateAccountMail;
use App\jobs\SendResetPasswordMail;

trait AdminSupportTrait
{
    /**
     * 根据邮箱地址返回邮箱登陆页面
     */
    public function getMailHost($mail)
    {
        $t=explode('@', $mail);
        $t=strtolower($t[1]);
        if ($t=='163.com') {
            return 'mail.163.com';
        } elseif ($t=='vip.163.com') {
            return 'vip.163.com';
        } elseif ($t=='126.com') {
            return 'mail.126.com';
        } elseif ($t=='qq.com'||$t=='vip.qq.com'||$t=='foxmail.com') {
            return 'mail.qq.com';
        } elseif ($t=='gmail.com') {
            return 'mail.google.com';
        } elseif ($t=='sohu.com') {
            return 'mail.sohu.com';
        } elseif ($t=='tom.com') {
            return 'mail.tom.com';
        } elseif ($t=='vip.sina.com') {
            return 'vip.sina.com';
        } elseif ($t=='sina.com.cn'||$t=='sina.com') {
            return 'mail.sina.com.cn';
        } elseif ($t=='yahoo.com.cn'||$t=='yahoo.cn') {
            return 'mail.cn.yahoo.com';
        } elseif ($t=='yeah.net') {
            return 'www.yeah.net';
        } elseif ($t=='21cn.com') {
            return 'mail.21cn.com';
        } elseif ($t=='hotmail.com') {
            return 'www.hotmail.com';
        } elseif ($t=='sogou.com') {
            return 'mail.sogou.com';
        } elseif ($t=='188.com') {
            return 'www.188.com';
        } elseif ($t=='139.com') {
            return 'mail.10086.cn';
        } elseif ($t=='189.cn') {
            return 'webmail15.189.cn/webmail';
        } elseif ($t=='wo.com.cn') {
            return 'mail.wo.com.cn/smsmail';
        } elseif ($t=='139.com') {
            return 'mail.10086.cn';
        } else {
            return '';
        }
    }

    /**
     * 发送激活邮件
     */
    public function sendActivateAccountMail($mail)
    {
        //生成临时token
        // $mail['token'] = encrypt($mail['mail']);
        $mail['token'] = md5('laravel13341334'.$mail['mail']);

        //写入redis缓存,设置过期时间为24小时
        $redis = Redis::connection('admin');
        $redis->setex('activatetoken:'.$mail['token'], 43200, $mail['id']);

        //选择config/queue下的默认队列
        $this->dispatch(new SendActivateAccountMail($mail));
    }

    /**
     * 通过activatetoken查询admin的id
     */
    public function findActivateToken($token)
    {
        if (is_string($token)) {
            $redis = Redis::connection('admin');
            $id = $redis->get('activatetoken:'.$token);
            if ($id) {
                $redis->del('activatetoken:'.$token);
                return $id;
            }
        }
    }

    /**
     * 发送重置密码邮件
     */
    public function sendResetPasswordMail($mail)
    {
        //生成临时token
        // $mail['token'] = encrypt($mail['mail']);
        $mail['token'] = str_random(20);
        
        //写入redis缓存,设置过期时间为10分钟
        $redis = Redis::connection('admin');
        $redis->setex('resettoken:'.$mail['token'], 600, $mail['id']);

        //选择config/queue下的默认队列
        $this->dispatch(new SendResetPasswordMail($mail));
    }

    /**
     * 通过resettoken查询admin的id
     */
    public function findResetToken($token)
    {
        if (is_string($token)) {
            $redis = Redis::connection('admin');
            $id = $redis->get('resettoken:'.$token);
            return $id;
        }
    }

    /**
     * 密码重置成功，删除resettoken
     */
    public function delResetToken($token)
    {
        if (is_string($token)) {
            $redis = Redis::connection('admin');
            $redis->del('resettoken:'.$token);
        }
    }
}
