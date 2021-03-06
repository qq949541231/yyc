<?php
namespace app\common\Tool;
class GetAddress{
	 static $key='K2LBZ-PTCKG-VKCQW-IV3PD-VAJDH-JNF4F'; //秘钥
     static $url='https://apis.map.qq.com/ws/geocoder/v1/';
     static $url_distance='https://apis.map.qq.com/ws/distance/v1/';

	/**
     * 获取地址 
     * $longitude 经度
     * $latitude 纬度
     */
    public static function getaddress($longitude,$latitude){
        $location=$latitude.','.$longitude;
        $param=array(
            'location'=>$location,
            'key'=>self::$key,
            'get_poi'=>0
        );
        $address_object=json_decode(self::mb_GetReq(self::$url,$param),true);
        if($address_object['status']==0){
            $address=array(
                "status"=>$address_object['status'],
                "location"=>$address_object['result']['location'],//lat 经度  lng 纬度
                "address"=>$address_object['result']['address']
            );
            return $address;
        }else{
            return $address_object;
        }
    }
    /**
     * 用地址获取经 纬度  
     * $address  地址
     */
    public static function getlocation($address){
         //地址
        $param=array(
            'address'=>$address,
            'key'=>self::$key,
         //   'region'=>'中山市' //提示可以考虑自己设置先
        );
        $address_object=json_decode(self::mb_GetReq(self::$url,$param),true);
        if($address_object['status']==0){
            $address=array(
                "status"=>$address_object['status'],
                "latitude"=>$address_object['result']['location']['lat'],//lat 经度  lng 纬度
                "longitude"=>$address_object['result']['location']['lng'],//lat 经度  lng 纬度
            );
            return $address;
        }else{
            exception($address_object['message'], $address_object['status']);
        }
    }
    /**
     * 获取距离  （直线距离） 米 两经纬度距离
     * latitude1
     * longitude1
     * 
     * latitude2
     * longitude2
     */
    public static function getdistance($data){//经度,纬度
            // 将角度转为狐度 
            $radLat1 = deg2rad($data['latitude1']); //deg2rad()函数将角度转换为弧度
            $radLng1 = deg2rad($data['longitude1']);
            
            $radLat2 = deg2rad($data['latitude2']);
            $radLng2 = deg2rad($data['longitude2']);

            $a = $radLat1 - $radLat2;
            $b = $radLng1 - $radLng2;
            $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;
            return $s;
      
    }
    /**
     * 计算某个经纬度的周围某段距离的正方形的四个点
     * 通过获取4个点，从而获取到经度的最大值与最小值已经纬度的最大最小值，通过数据库查询获取到在此范围内的数据
     * @param       radius 地球半径 平均6371km
     * @param    lng float 经度
     * @param      lat float 纬度
     * @param    distance float 该点所在圆的半径，该圆与此正方形内切，默认值为1千米
     * @return array 正方形的四个点的经纬度坐标
     */
    public static function returnSquarePoint($lng, $lat, $distance = 1, $radius = 6371)
    {
        $dlng = 2 * asin(sin($distance / (2 * $radius)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);

        $dlat = $distance / $radius;
        $dlat = rad2deg($dlat);
        return array(
            'left-top' => array(
                'lat' => $lat + $dlat,
                'lng' => $lng - $dlng
            ),
            'right-top' => array(
                'lat' => $lat + $dlat,
                'lng' => $lng + $dlng
            ),
            'left-bottom' => array(
                'lat' => $lat - $dlat,
                'lng' => $lng - $dlng
            ),
            'right-bottom' => array(
                'lat' => $lat - $dlat,
                'lng' => $lng + $dlng
            )
        );
    }
}