<?php  
//1. 将timestamp , nonce , token 按照字典排序  
$timestamp = $_GET['timestamp'];  
$nonce = $_GET['nonce'];  
$token = "xuyuwei";  
$signature = $_GET['signature'];  
$array = array($timestamp,$nonce,$token);  
sort($array);  
  
//2.将排序后的三个参数拼接后用sha1加密  
$tmpstr = implode('',$array);  
$tmpstr = sha1($tmpstr);  
  
//3. 将加密后的字符串与 signature 进行对比, 判断该请求是否来自微信  
if($tmpstr == $signature)  
{  
    echo $_GET['echostr'];  
    exit;  
}
        //1.获取到微信推送过来post数据（xml格式）
        $postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
        //2.处理消息类型，并设置回复类型和内容
        $postObj = simplexml_load_string( $postArr );
        //判断该数据包是否是订阅的事件推送
        if( strtolower( $postObj->MsgType) == 'event'){
            //如果是关注 subscribe 事件
            if( strtolower($postObj->Event == 'subscribe') ){
                //回复用户消息(纯文本格式)
                $toUser   = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  =  'text';
                $content  = '欢迎关注，这里是一个简单的小测试哦，回复想要查询的天气情况试一试吧！';
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                echo $info;

            }
          
        }
        //根据用户输入来返回不同信息
       else if(strtolower($postObj->MsgType)=='text'){
                $content = $postObj->Content;
         if(strstr($content, "天气")){
           //获取中文字符“XX天气”前两个中文
                $code=mb_substr($content , 0 , 2 , 'utf-8');
            //回复用户消息(纯文本格式)
                $toUser   = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  =  'text';
                $content  = $code."的天气为：\n";
                $content .="天气状况：晴\n";
           $content .="最低温：-100℃\n";
           $content .="最高温：100℃\n";
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                echo $info;
         }
         else{
                //回复用户消息(纯文本格式)
                $toUser   = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  =  'text';
                
                $content  = '您发送的内容是：'.$content;
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                echo $info;
         }
           }

?>