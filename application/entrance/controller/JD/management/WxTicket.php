<?php


namespace app\entrance\controller\JD\management;


use app\entrance\base\BaseController;

use app\entrance\model\Ticket;

use think\Request;


/**
 * @title 小程序端接口
 * @description 接口说明
 * @header name:token type:varchar require:1 default:1 other:'' desc:数据表明
 */
class WxTicket extends BaseController

{

    //查找门票数据

    public function ticket_select($request){

        $length = $request->param('length');

        $offset = $request->param('offset');

        $keyword = $request->param('keyword');

        if($keyword != null){

            //模糊搜索

            $data = Ticket::where('name','like',"%".$keyword."%")

            ->limit($offset,$length)->select();

            $count = $data->count();

        }

        else if($offset != null){

            //limit分页

            $data = Ticket::limit($offset,$length)->select();

            $count = Ticket::count();

        }

        else{

            $data = Ticket::where('status','1')->select();

            $count = $data->count();

        }

        return $this->jsonSuccessData($data,$count);  

    }


    //删除门票数据


    public function ticket_delete($request){

        $a = $request->param('id');

        $message  = Ticket::where('id',$a)->delete();

        return $this->jsonSuccessData($message);

    }

    //查找单条记录

    public function ticket_find($request){

        $id = $request->param('id');

        $message = Ticket::where('id', $id)->find();

        $status = Ticket::where('id', $id)->value('status');

        if($status == '1'){


            $swit = true;

        }

        else if($status == '2'){

            $swit = false;

        }

        return $this->jsonSuccessData($message,$swit);

    }

    

    //更新修改门票数据

    public function ticket_update($request){
    
        $id = $request->param('id');
    
        $name = $request->param('name');
    
        $icon = $request->param('icon');
    
        $price = $request->param('price');
    
        $swit = $request->param('swit');
    
        if($swit == 'true'){
    
            $status = '1';
    
        }
    
        else if($swit == 'false'){
    
            $status = '2';
    
        }
    
        $message = Ticket::where('id', $id)->update([
    
            'name' => $name,
    
            'price'=>$price,
    
            'icon' => $icon,
    
            'status' => $status,
    
        ]);
            
        return $this->jsonSuccessData($message);
    }


    //增加门票数据

    public function ticket_create($request){
    
        $name = $request->param('name');
    
        $icon = $request->param('icon');
    
        $price = $request->param('price');
    
        $swit = $request->param('swit');
    
        if($swit == 'true'){
    
            $status = '1';
    
        }
    
        else if($swit == 'false'){
    
            $status = '2';
    
        }
    
        $data = [
    
            'name' => $name,
    
            'price'=>$price,
    
            'icon' => $icon,
    
            'status' => $status,
    
        ];
    
        $message = Ticket::insert($data);
    
        return $this->jsonSuccessData($message);
    
    }


}
