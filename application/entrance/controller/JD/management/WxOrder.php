<?php


namespace app\entrance\controller\JD\management;


use app\entrance\base\BaseController;

use app\entrance\model\Order;

use think\Request;



class WxOrder extends BaseController

{


    
    //订单查询
    
    public function order_select($request){
    
        $length = $request->param('length');
    
        $offset = $request->param('offset');
    
        $keyword = $request->param('keyword');
    
        $out_trade_no = $request->param('out_trade_no');
    
        $user_id = $request->param('user_id');
    
        $use_status = $request->param('use_status');
    
        $pay_status = $request->param('pay_status');
    
        if($out_trade_no != null){
    
            //模糊搜索
    
            $data = Order::where('out_trade_no','like',"%".$out_trade_no."%")
    
            ->find();
    
            return $this->jsonSuccessData($data);
    
        }
    
        else if($keyword != null){
    
            //模糊搜索
    
            $data = Order::where('out_trade_no','like',"%".$keyword."%")
    
            ->limit($offset,$length)->select();
    
            $count = $data->count();
    
            return $this->jsonSuccessData($data,$count);
    
        }
    
        else if($length != null){
    
            //limit分页
    
            $data = Order::limit($offset,$length)->select();
    
            $count = Order::count();
    
            return $this->jsonSuccessData($data,$count);
    
        }
    
        else if($use_status == 2){
    
            $data = Order::where('use_status',$use_status)->select();
    
            $count = Order::count();
    
            return $this->jsonSuccessData($data,$count);
    
        }
    
        else{
    
            $data = Order::where('user_id',$user_id)->where('pay_status',$pay_status)->select();
    
            $count = Order::count();
    
            return $this->jsonSuccessData($data,$count);
    
        }
    
    }




    
    //删除订单数据




    
    public function order_delete($request){
    
        $a = $request->param('id');

        $out_trade_no = $request->param('out_trade_no');

        if($a != null){

            $message  = Order::where('id',$a)->delete();

        }

        else if($out_trade_no != null){

            $message  = Order::where('out_trade_no',$out_trade_no)->delete();

        }

        return $this->jsonSuccessData($message);

    }


    //查找单条记录

    public function order_find($request){

        $id = $request->param('id');

        $message = Order::where('id', $id)->find();

        return $this->jsonSuccessData($message);

    }

    


    //更新修改订单数据


    public function order_update($request){

        $id = $request->param('id');

        $out_trade_no = $request->param('out_trade_no');

        $count = $request->param('count');

        $total_price = $request->param('total_price');

        $pay_status = $request->param('pay_status');

        $effective_time = $request->param('effective_time');

        $use_status = $request->param('use_status');

        $message = Order::where('id', $id)->update([

            'out_trade_no' => $out_trade_no,

            'count'=>$count,

            'total_price' => $total_price,

            'pay_status' => $pay_status,

            'use_status'=>$use_status,

            'effective_time' => $effective_time,

        ]);

            
        return $this->jsonSuccessData($message);
    }




}