<h1>
    来自{{config('app.name')}}的密码重置邮件！
</h1>
<hr>
<p>
    {{$user}}，下面进行密码重置！
    <br>
    感谢使用{{config('app.name')}}！
    <br>
    请点击下面的链接进入密码重置页面,该地址有效期为10分钟：
    <br>
    <a href="{{ url('admin/password/reset/'.$token) }}" target="_blank" >{{ url('admin/confirm/'.$token) }}</a>
    <br>
    如果上面链接无法点击，请复制链接内容到浏览器的地址栏中进行激活。
</p>
