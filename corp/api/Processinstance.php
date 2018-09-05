<?php
namespace corp\api;
use corp\util;

/*审批实例*/

class Processinstance
{
    private $http;
    public function __construct() {
        $this->http = new \corp\util\Http();
    }
	
	/*批量获取审批实例id*/
	public function listids($accessToken,$process_code, $time=[],$cursor=1){
		
		$data=[
			'process_code'=>$process_code,
			'start_time'=>!empty($time['start_time'])?$time['start_time']*1000:strtotime(date("Y-m-d",time()))*1000,//默认当天0点
			'end_time'=>!empty($time['end_time'])?$time['end_time']*1000:time()*1000,//默认当前时间	
			'size'=>'20',//每页20个
			'cursor'=>$cursor,
		];
		$response = $this->http->post("/topapi/processinstance/listids", 
        array("access_token" => $accessToken), 
        json_encode($data));
		dump($data);
        return $response;
	}
	

    /*获取单个审批实例*/
	public function gets($accessToken,$process_instance_id){
		$response = $this->http->post("/topapi/processinstance/get", 
            array("access_token" => $accessToken), 
            json_encode(array('process_instance_id'=>$process_instance_id)));
        return $response;
	}
	
	
}