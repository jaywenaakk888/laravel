<?php

namespace App\Http\Controllers\Wechat;

trait WeChatSupportTrait{

    /**
     * 返回事件消息处理结果
     */
    public function responseMsgByEvent($postObj){
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $event = $postObj->Event;
        $eventKey = $postObj->EventKey;
        $time = time();
        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[%s]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        </xml>";
        if(!empty( $eventKey ))
        {
            $msgType = "text";
            $contentStr = '事件消息:id为'.$eventKey;
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        }
    }

    /**
     * 返回文字消息处理结果
     */
    public function responseMsgByText($postObj){
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $content = trim($postObj->Content);
        $msgId = $postObj->MsgId;
        $time = time();
        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[%s]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        </xml>";
        if(!empty( $content ))
        {
            $msgType = "text";
            $contentStr = '文字消息：'.$content;
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        }
    }

    /**
     * 返回图片消息处理结果
     */
    public function responseMsgByImage($postObj){
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $picUrl = $postObj->PicUrl;
        $mediaId = $postObj->MediaId;
        $msgId = $postObj->MsgId;        
        $time = time();
        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[%s]]></MsgType>
        <ArticleCount>1</ArticleCount>
        <Articles>
        <item>
        <Title><![CDATA[%s]]></Title> 
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
        </item>
        </Articles>
        </xml>";
        if(!empty( $mediaId ))
        {
            // $msgType = "text";
            $contentStr = '接收图片消息';
            // $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            $msgType = "news";
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType,$contentStr,'返回测试图文信息',$picUrl,'http://139.199.8.195/');
            echo $resultStr;
        }
    }

    /**
     * 返回语音消息处理结果
     */
    public function responseMsgByVoice($postObj){
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;    
        $mediaId = $postObj->MediaId;  
        $time = time();      
        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[%s]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        </xml>";
        if(!empty( $mediaId ))
        {
            $msgType = "text";
            $contentStr = '语音消息';
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        }
    }

    /**
     * 返回视频消息处理结果
     */
    public function responseMsgByVideo($postObj){
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $mediaId = $postObj->MediaId;    
        $time = time();   
        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[%s]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        </xml>";
        if(!empty( $mediaId ))
        {
            $msgType = "text";
            $contentStr = '视频消息';
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        }
    }

    /**
     * 返回坐标消息处理结果
     */
    public function responseMsgByLocation($postObj){
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $label = $postObj->Label;    
        $time = time();   
        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[%s]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        </xml>";
        if(!empty( $label ))
        {
            $msgType = "text";
            $contentStr = '坐标消息'.$label;
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        }
    }

    /**
     * 返回链接消息处理结果
     */
    public function responseMsgByLink($postObj){
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $title = $postObj->title;
        $url = $postObj->Url;    
        $time = time();   
        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[%s]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        </xml>";
        if(!empty( $url ))
        {
            $msgType = "text";
            $contentStr = '链接消息'.$title;
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        }
    }

    /**
     * 返回其他消息处理结果
     */
    public function responseMsgByOther($postObj){
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $time = time();
        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[%s]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        </xml>";
        if(!empty( $eventKey ))
        {
            $msgType = "text";
            $contentStr = '无法识别消息';
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        }
    }




}