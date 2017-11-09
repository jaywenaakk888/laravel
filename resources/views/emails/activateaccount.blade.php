<h1>
    来自{{config('app.name')}}的账号激活邮件！
</h1>
<hr>
<p>
    {{$user}}，感谢您注册会员！
    <br>
    欢迎加入{{config('app.name')}}！
    <br>
    请点击下面的链接完成注册,该地址有效期为24小时：
    <br>
    <a href="{{ url('admin/confirm/'.$token) }}" target="_blank" >{{ url('admin/confirm/'.$token) }}</a>
    <br>
    如果上面链接无法点击，请复制链接内容到浏览器的地址栏中进行激活。
</p>
