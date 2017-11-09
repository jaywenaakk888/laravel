@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">登录</div>

                <div class="panel-body">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                    <strong>注意!</strong> 输入出错：<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    </div>
                @endif
                @if (Session::has('success'))
                <div class="alert alert-success" >
                <p>{{ Session::get('success') }}</p>
                </div>
                @endif
                @if (Session::has('message'))
                <div class="alert alert-success" >
                <a href="http://{{ Session::get('message')['mail_host'] }}" target="_blank" >{{ Session::get('message')['message'] }}</a>
                </div>
                @endif
                    <form class="form-horizontal" method="POST" action="{{ url('admin/signin') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">邮箱地址</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">密码</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- captcha -->
						<div class="form-group{{ $errors->has('captcha') ? ' has-error' : '' }}">
							<label for="captcha" class="col-md-4 control-label">验证码</label>
							<div class="col-md-6">
								<p><img src="{{ captcha_src() }}" onclick="this.src='{{ captcha_src() }}?r='+Math.random();" alt="">点击图片更换验证码</p>
								<p><input type="text" class="form-control" name="captcha"><p>
                                @if ($errors->has('captcha'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('captcha') }}</strong>
                                    </span>
                                @endif
							</div>
						</div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> 一周内自动登录
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    登录
                                </button>

                                <a class="btn btn-link" href="{{ url('admin/password') }}">
                                    忘记密码?点击找回。
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
