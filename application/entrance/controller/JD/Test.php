<?php


namespace app\entrance\controller\JD;


use app\entrance\base\BaseController;



class Test extends BaseController
{

    public function index(){

        return $this->tableName;
    }

}
