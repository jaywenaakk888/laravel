<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Admin;
use Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class AdminController extends Controller
{
    use AdminSupportTrait;
    /**
     * 登陆视图
     */
    public function login(Request $request)
    {
        //基于cookie及session的自动登录
        if ($request->cookie('admin')&&$request->cookie('token')) {
            if (is_int($request->cookie('admin'))) {
                $admin = Admin::find($request->cookie('admin'));
                if ($admin['remember_token']==$request->cookie('token')) {
                    $request->session()->put('admin', ['id'=>$admin['id'],'name'=>$admin['name']]);
                    return Redirect::to('home');
                } else {
                    if ($request->session()->has('admin')) {
                        return Redirect::to('home');
                    } else {
                        return view('admin/login');
                    }
                }
            }
        } else {
            if ($request->session()->has('admin')) {
                return Redirect::to('home');
            } else {
                return view('admin/login');
            }
        }
    }

    /**
     * 登陆操作
     */
    public function signIn(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'captcha' => 'required|captcha',
        ]);

        $admin = Admin::where('email', $request->input('email'))->first();
        $bool = Hash::check($request->input('password'), $admin['password']);
        if ($bool) {
            if ($admin['state']==1) {
                if ($request->has('remember')) {
                    //再次登录时自动生成token
                    $token = str_random(20);
                    //写入登录信息
                    Cookie::queue('admin', $admin['id'], 10080);
                    Cookie::queue('token', $token, 10080);
                    $request->session()->put('admin', ['id'=>$admin['id'],'name'=>$admin['name']]);
                    //保存token
                    $admin->remember_token = $token;
                    $admin->save();
                } else {
                    $request->session()->put('admin', ['id'=>$admin['id'],'name'=>$admin['name']]);
                }
                return Redirect::to('home');
            } else {
                $mail_host = $this->getMailHost($request->input('email'));
                return Redirect::back()->withInput()->with('message', ['mail_host'=>$mail_host,'message'=>'该账户没有激活，点击此处可以登录邮箱查找激活邮件进行账户激活，若激活信息已过期，可点击下面忘记密码重新设置密码并激活账户！']);
            }
        } else {
            return Redirect::back()->withInput()->withErrors('邮箱或密码错误！');
        }
    }

    /**
     * 注册视图
     */
    public function register()
    {
        return view('admin/register');
    }

    /**
     * 注册操作
     */
    public function signup(Request $request)
    {
        //验证表单
        $this->validate($request, [
            'name' => 'required|string|max:255|unique:admins',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:6|confirmed',
            'captcha' => 'required|captcha',
        ]);

        //插入数据
        $admin = Admin::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'state' => '0',
        ]);

        if ($admin) {
            //发送激活邮件
            $mail = ['user'=>$request->input('name'),'mail'=>$request->input('email'),'id'=>$admin->id];
            $this->sendActivateAccountMail($mail);

            //返回登录页
            $mail_host = $this->getMailHost($request->input('email'));
            return Redirect::to('admin/login')->with('message', ['mail_host'=>$mail_host,'message'=>'注册成功，点击此处可以登录邮箱进行账户激活！']);
        } else {
            return Redirect::back()->withInput()->withErrors('注册失败');
        }
    }

    /**
     * 确认注册信息
     */
    public function confirm($id, Request $request)
    {
        //查找缓存里面mail
        $admin_id = $this->findActivateToken($id);
        if ($admin_id) {
            $admin = Admin::find($admin_id);
            $admin->state = 1;
            if ($admin->save()) {
                $request->session()->put('admin', ['id'=>$admin['id'],'name'=>$admin['name']]);
                return Redirect::to('admin/login');
            }
        } else {
            return Redirect::to('admin/login')->withErrors('激活信息已过期，可点击下面忘记密码重新设置密码并激活账户！');
        }
    }

    /**
     * 退出登录
     */
    public function logout(Request $request)
    {
        $request->session()->flush();
        Cookie::queue('admin', null, -1); // 销毁
        Cookie::queue('token', null, -1); // 销毁
        return Redirect::to('home');
    }

    /**
     * 找回密码视图
     */
    public function password()
    {
        return view('admin/passwords/email');
    }

    /**
     * 发送邮件找回密码
     */
    public function findPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email',
            'captcha' => 'required|captcha',
        ]);
        $admin = Admin::where('email', $request->input('email'))->first();
        if ($admin) {
            $mail = ['user'=>$admin['name'],'mail'=>$admin['email'],'id'=>$admin['id']];
            $this->sendResetPasswordMail($mail);
            $mail_host = $this->getMailHost($request->input('email'));
            return Redirect::back()->withInput()->with('message', ['mail_host'=>$mail_host,'message'=>'重置密码链接已发送到指定邮箱，点击此处可以登录邮箱进行重置密码！']);
        } else {
            return Redirect::back()->withInput()->withErrors('邮箱地址错误!');
        }
    }

    /**
     * 重置密码视图
     */
    public function reset($id)
    {
        return view('admin/passwords/reset')->with('token', $id);
    }

    /**
     * 重置密码操作
     */
    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'token' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string|min:6|confirmed',
            'captcha' => 'required|captcha',
        ]);

        //查找缓存里面mail
        $admin_id = $this->findResetToken($request->input('token'));
        if ($admin_id) {
            $admin = Admin::find($admin_id);
            if ($admin['email']==$request->input('email')) {
                $admin->state = 1;
                $admin->password = bcrypt($request->input('password'));
                if ($admin->save()) {
                    //重置密码成功是删除缓存里面的token
                    $this->delResetToken($request->input('token'));
                    return Redirect::to('admin/login')->with('success', '密码重置成功，请使用新密码登陆！');
                } else {
                    return Redirect::back()->withInput()->withErrors('重置密码失败!');
                }
            } else {
                return Redirect::back()->withInput()->withErrors('邮箱地址与申请重置密码的邮箱地址不符，请输入正确的邮箱地址!');
            }
        } else {
            return Redirect::back()->withInput()->withErrors('该验证地址已失效，请返回登录页面重新申请重置密码！');
        }
    }
}
