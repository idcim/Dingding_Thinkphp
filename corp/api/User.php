<?php
namespace corp\api;
use corp\util;
//require_once(__DIR__ . "/../util/Http.php");

class User
{
    private $http;
    public function __construct() {
        $this->http = new \corp\util\Http();
    }   

    public function getUserInfo($accessToken, $code)
    {
        $response = $this->http->get("/user/getuserinfo", 
            array("access_token" => $accessToken, "code" => $code));
        return $response;
    }
	
	/*user/get 获取成员 GET请求*/
    public function get($accessToken, $userId)
    {
        $response = $this->http->get("/user/get",
            array("access_token" => $accessToken, "userid" => $userId));
        return $response;
    }

    public function simplelist($accessToken,$deptId){
        $response = $this->http->get("/user/simplelist",
            array("access_token" => $accessToken,"department_id"=>$deptId));
        return $response;

    }
	
	/*获取部门用户（详情）*/
	public function ulist($accessToken,$deptId){
        $response = $this->http->get("/user/list",
            array("access_token" => $accessToken,"department_id"=>$deptId));
        return $response;

    }
}