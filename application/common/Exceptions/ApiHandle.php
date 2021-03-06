<?php

namespace app\common\Exceptions;

use app\common\Response\ResponseJson;
use Exception;
use think\exception\Handle;
use think\exception\HttpException;
use app\entrance\base\ApiErrDesc;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class ApiHandle extends Handle
{   
    use ResponseJson;
    public function render(Exception $e)
    {   
        $log = new Logger('Eect');
        $log->pushHandler(new StreamHandler(__DIR__.'/log'.'/'.date('Ym').'/'.date('d').'.log',Logger::WARNING));
        // 参数验证错误
        if ($e instanceof ApiErrDesc) {
            $code=$e->getCode();
            $message=$e->getMessage();
        }else{
            $code=$e->getCode();
            if(!$code||$code<0){
                $code = ApiErrDesc::UNKNOWN_ERR[0];
            }
            $message=$e->getMessage()?:ApiErrDesc::UNKNOWN_ERR[1];
        }
        // 请求异常
        if ($e instanceof HttpException && request()->isAjax()) {
            $log->addWarning('请求异常',array('code'=>$e->getStatusCode(),'message'=>$e->getMessage()));
            return response($e->getMessage(), $e->getStatusCode());
        }

        // 其他错误交给系统处理
        $log->addWarning('其他错误交给系统处理',array('code'=>$code,'message'=>$message));
        return response($this->jsonData($code,$message));
    }
}
