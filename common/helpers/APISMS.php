<?php
namespace common\helpers;
class APISMS
{
    /**
     *  Gưi tin cho 1 số điện thoại
     */
    public function sent($user_api,$password_api,$brandname,$dest_addr,$message,$target){
        //$url = 'http://sms.vnet.vn:8082/api/sent?username='.$user_api.'&password='.$password_api.'&source_addr='.$brandname.'&dest_addr='.$dest_addr.'&message='.$this->myUrlEncode($message).'';
        //$url = 'http://g3g4.vn/smsws/api/sendSms.jsp?username='.$user_api.'&password='.$password_api.'&content='.$this->myUrlEncode($message).'&type=2&mobile=$dest_addr&brandname='.$brandname.'&target='.$target.'';
        $url = 'http://g3g4.vn/smsws/api/sendSms.jsp?username='.$user_api.'&password='.$password_api.'&content='.$this->myUrlEncode($message).'&type=2&mobile='.$dest_addr.'&brandname='.$this->myUrlEncode($brandname).'&target='.$target.'';
        $result = self::callApi($url);
        return $result;
    }
    
    // Gửi nhiều tin nhắn
    public function sent_multi(){
        
    }
    
    // Lấy trạng thai của 1 tin nhắn đã gửi
    public function getDeliverReport($user_api,$password_api){
        $url = 'http://sms.vnet.vn:8082/api/getDelivery?username='.$user_api.'&password='.$password_api.'&msgid=559205568';
        $result = self::callApi($url);
        return $result;
    }

    public function getCashBalance($user_api,$password_api){
        //$url = 'http://sms.vnet.vn:8082/api/getBalance?username='.$this->myUrlEncode($user_api).'&password='.$this->myUrlEncode($password_api);
        //$result = self::callApi($url);
        $result = "";
        return $result;
    }
    
    public static function callApi($url)
    {

//        $headers = CMap::mergeArray(array("Cache-Control: no-cache"), $headers);
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
//        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($handle, CURLOPT_FRESH_CONNECT, true);
        $response = curl_exec($handle);
//        curl_close($handle);
        return $response;
    }
    

    public function myUrlEncode($string) {
        $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
        $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
        return str_replace($entities, $replacements, urlencode($string));
    }

}
?>

