<?php
namespace app\common\Response;

trait ResponseJson
{
    /**api 异常返回 */
    public function jsonData($code,$message,$data=[]){
        return $this->jsonResponse($code,$message,$data);
    }

    /**api 成功返回 */
    public function jsonSuccessData($data=[],$count=0){
        return $this->jsonResponse(0,'success',$data,$count);
    }

    /**返回一个json */
    public function jsonResponse($code,$message,$data,$count=0){
        $content=[
            'code'=>$code,
            'message'=>$message,
            'data'=>$data,
            'count'=>$count
        ];
        return json_encode($content);
    }
}
