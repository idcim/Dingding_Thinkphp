<?php
namespace corp;
use Config;
use \corp\api;
use \corp\util\Http;
use \corp\util\Cache;
use \corp\util\Log;
use \corp\api\User;
use \corp\api\Message;

define('DIR_ROOT', dirname(__FILE__).'/');
define("OAPI_HOST", Config('OAPI_HOST'));

class corp{
	private $accessToken;
   	private $jsTicket;
	

    function __construct()
    {	
        $this->accessToken = corp::getAccessToken();
        $this->jsTicket = corp::getTicket($this->accessToken);
    }
	
	function getAccessToken(){
		$auth = new \corp\api\Auths();
		$config=Config();

		
		$token=$auth->getAccessToken($config['app']['CORPID'],$config['app']['SECRET']);
		return $token;
	}
	
	
	function getTicket($accessToken){
		$auth = new \corp\api\Auths();
		
		$Ticket=$auth->getTicket($accessToken);
		
		return $Ticket;
	}
	
	//用户类型数据获得
	function getUser($api_type,$id){
		$user = new User();
		
		switch ($api_type) {
		case '':
	        $json= array("error_code"=>"4000");
	        break;
	    case 'getuserid':
			//获得指定用户ID
	        $accessToken = $this->accessToken;
	        $code = $id;
	        $userInfo = $user->getUserInfo($accessToken, $code);
	        //Log::i("[USERINFO-getuserid]".json_encode($userInfo));
	        $arr=$userInfo;
	        break;
	
	    case 'get_userinfo':
			//获得指定用户信息详情
	        $accessToken = $this->accessToken;
	        $userid = $id;
	        $userInfo = $user->get($accessToken, $userid);
	        //Log::i("[get_userinfo]".json_encode($userInfo));
	        $arr=$userInfo;
	        break;
		case 'simplelist':
			//获得部门人员及ID
	        $accessToken = $this->accessToken;
	        $deptId = $id;
	        $deptInfo = $user->simplelist($accessToken, $deptId);
	        $arr=$deptInfo;
	        break;
	    case 'jsapi-oauth':
	        $href = $_GET["href"];
	        $configs = $auth->getConfig($href);
	        $configs['errcode'] = 0;
	       $json=json_encode($configs, JSON_UNESCAPED_SLASHES);
	        break;
		}
		
		//dump($this->accessToken);
		return $arr;
	}

	//获得所有部门信息
	function getDept(){
		$dept = new Department();
		
		$accessToken = $this->accessToken;
        $deplist = $dept->listDept($accessToken);
        
		return $deplist;
	}
	
}


?>