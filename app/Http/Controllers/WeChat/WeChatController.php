<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use EasyWeChat\OfficialAccount\Application;
use Illuminate\Support\Facades\Redis;

class WeChatController extends Controller
{
	use WeChatSupportTrait;
    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve(Request $request)
    {
		// $this->valid();    //服务器验证
		$this->responseMsg($request);
	}

	/**
	 * 信息处理回复
	 */
	private function responseMsg($request)
    {
		// $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		$postStr = $request->getContent();
		$redis = Redis::connection('wechat');
		$redis->set('temp', $postStr);

        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$msgType = $postObj->MsgType;
			switch ($msgType) {
				case 'event':
					return $this->responseMsgByEvent($postObj);
					break;
				case 'text':
					return $this->responseMsgByText($postObj);
					break;
				case 'image':
					return $this->responseMsgByImage($postObj);
					break;
				case 'voice':
					return $this->responseMsgByVoice($postObj);
					break;
				case 'video':
					return $this->responseMsgByVideo($postObj);
					break;
				case 'location':
					return $this->responseMsgByLocation($postObj);
					break;
				case 'link':
					return $this->responseMsgByLink($postObj);
					break;
				// ... 其它消息
				default:
					return $this->responseMsgByOther($postObj);
					break;
			}

        }
    }

	/**
	 * 服务器配置验证
	 */
	private function valid($request)
    {
        $echoStr = $request->input('echostr');
        if($this->checkSignature($request)){
            echo $echoStr;
            exit;
        }
    }
	/**
	 * 服务器配置验证规则
	 */
    private function checkSignature($request)
    {
        $signature = $request->input('signature');
        $timestamp = $request->input('timestamp');
        $nonce = $request->input('nonce');
        $token =config('wechat.token');
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
	/**
	 * 使用接口创建菜单
	 */
    public function handle()
    {
		if(empty(self::read_token())){
			self::build_access_token();
		}
		$data = '{
			"button": [
				{           
					"name":"菜单",
					"sub_button":[
					{	
						"type":"view",
						"name":"文章",
						"url":"http://139.199.8.195/"
					 },
					 {	
						"type":"click",
						"name":"今日歌曲",
						"key":"V1001_TODAY_MUSIC"
					 },
					]
				},
			]
		}';
		
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".self::read_token();
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		if (!empty($data)){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$rs = json_decode(curl_exec($ch));
		if(isset($rs->errcode) && $rs->errcode != 0){
			echo $rs->errcode;
			echo $rs->errmsg;			
		}else{
			echo $rs->errmsg;	
		}
		curl_close($ch);
		// echo $data;
	}

	//获取access_token并保存到redis里面
	public static function build_access_token(){
		$ch = curl_init(); //初始化一个CURL对象
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.config('wechat.app_id').'&secret='.config('wechat.secret');
		curl_setopt($ch, CURLOPT_URL, $url);//设置你所需要抓取的URL
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置curl参数，要求结果是否输出到屏幕上，为true的时候是不返回到网页中,假设上面的0换成1的话，那么接下来的$data就需要echo一下。
		$rs = json_decode(curl_exec($ch));
		if(isset($rs->errcode) && $rs->errcode != 0){
			echo $rs->errcode;
			echo $rs->errmsg;			
		}else{
			// $token_file = fopen("token.txt","w") or die("Unable to open file!");//打开token.txt文件，没有会新建
			// fwrite($token_file,$rs->access_token);//重写tken.txt全部内容
			// fclose($token_file);//关闭文件流

			$redis = Redis::connection('wechat');
			$redis->setex('accesstoken', 7200, $rs->access_token);
		}
		curl_close($ch);
	}
	
	//设置定时器，每两小时执行一次build_access_token()函数获取一次access_token
	public static function set_interval(){
		ignore_user_abort();//关闭浏览器仍然执行
		set_time_limit(0);//让程序一直执行下去
		$interval = 7200;//每隔一定时间运行
		do{
			build_access_token();
			sleep($interval);//等待时间，进行下一次操作。
		}while(true);
	}
	
	//读取token
	public static function read_token(){
		// $token_file = fopen("token.txt", "r") or die("Unable to open file!");
		// $rs = fgets($token_file);
		// fclose($token_file);
		$redis = Redis::connection('wechat');
		$rs = $redis->get('accesstoken');
		return $rs;
	}
}
