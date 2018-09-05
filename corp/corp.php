<?php
namespace corp;
use Config;
use \corp\api;
use \corp\util\Http;
use \corp\util\Cache;
use \corp\util\Log;
use \corp\api\User;
use \corp\api\Message;
use \corp\api\Department;
use \corp\api\Processinstance;

define('DIR_ROOT', dirname(__FILE__).'/');
define("OAPI_HOST", Config::get('OAPI_HOST'));

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
		$config=Config::get();
		//dump($config);exit;
		$token=$auth->getAccessToken($config['app']['CORPID'],$config['app']['SECRET']);
		return $token;
	}
	
	
	function getTicket($accessToken){
		$auth = new \corp\api\Auths();
		
		$Ticket=$auth->getTicket($accessToken);
		
		return $Ticket;
	}
	
	//用户类型数据获得
	function getUser($api_type,$id=''){
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
			case 'list':
				//获得部门人员及ID
		        $accessToken = $this->accessToken;
		        $deptId = $id;
		        $deptInfo = $user->ulist($accessToken, $deptId);
		        $arr=$deptInfo;
		        break;
			
			case 'department':
				//获得所有部门信息
				$dept = new Department();
			
				$accessToken = $this->accessToken;
		        $arr = $dept->listDept($accessToken);
				
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

	/**审批数据
	 * @param $type string list|info
	 * @param $data array process_code|cursor|process_instance_id|start_time|end_time
	 * */
	function process($type,$data){
		$process=new Processinstance();
		
		switch ($type) {
			case 'list':
				//列出审批ID
				$accessToken = $this->accessToken;
				$arr=$process->listids($accessToken,$data['process_code'], ['start_time'=>$data['start_time'],'end_time'=>$data['end_time']],$data['cursor']);
				break;
				
			case 'info':
				//显示审批详情
				$accessToken = $this->accessToken;
				$arr=$process->gets($accessToken,$process_instance_id);
				break;
			default:	
				$arr=['errcode'=>404];
				break;
		}
		return $arr;
	}
	
}


?>