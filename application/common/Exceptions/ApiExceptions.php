<?php

namespace app\common\Exceptions;


use Throwabl;
use think\Exception;

class ApiExceptions extends \RuntimeException
{
    
    
    public function __construct(array $apiErrConst,Throwable $previous=null)
    {
        // var_dump($apiErrConst);
        $message=$apiErrConst[0];
        $code=$apiErrConst[1];

        parent::__construct($message,$code,$previous);
    }
}
