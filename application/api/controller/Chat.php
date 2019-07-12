<?php
namespace app\api\controller;
use GatewayWorker\Lib\Gateway;
use think\Controller;
use think\Db;
class Chat
{
    public function __construct(){
        Gateway::$registerAddress = '127.0.0.1:1238';
    }
    public function index()
    {
        return view(':index');
    }
 
    public function bind(){
//        $uid = input('post.uid');
//        $cmp_id = input('post.cmp_id');
//        $is_type = input('post.is_type');
//        $param = request()->param();
//        $client_id = $param['bind'];
//        $room =$uid.$cmp_id;
//        if($is_type==1){
//            Gateway::bindUid($client_id, $room);//gateway服务端 绑定
//            db('chat')->where('is_type',2)->where('cmp_id',$cmp_id)->where('uid',$uid)->setField('read1',1);
//            $sendData=$this->Mychat($uid,$cmp_id);
//            return json_encode($sendData);
//        }else{
//            db('chat')->where('is_type',1)->where('cmp_id',$cmp_id)->where('uid',$uid)->setField('read2',1);
//            Gateway::bindUid($client_id, $room);//gateway服务端 绑定
//            $sendData=$this->Mychat($uid,$cmp_id);
//            return json_encode($sendData);
//           // Gateway::sendToUid($cmp_id,json_encode($sendData));
//        }


        $uid = input('post.uid');
        $cmp_id = input('post.cmp_id');
        $is_type = input('post.is_type');
        $param = request()->param();
        $client_id = $param['bind'];
        $room =$uid.$cmp_id;
        if($is_type==1){
            Gateway::bindUid($client_id, $room);//gateway服务端 绑定
            db('chat')->where('is_type',2)->where('cmp_id',$cmp_id)->where('uid',$uid)->setField('read1',1);
            $sendData=$this->Mychat($uid,$cmp_id);
            return json_encode($sendData);
        }else{
            db('chat')->where('is_type',1)->where('cmp_id',$cmp_id)->where('uid',$uid)->setField('read2',1);
            Gateway::bindUid($client_id, $room);//gateway服务端 绑定
            $sendData=$this->Mychat($uid,$cmp_id);
            return json_encode($sendData);
            // Gateway::sendToUid($cmp_id,json_encode($sendData));
        }
 
    //    Gateway::joinGroup($client_id, 'web');
    }
 
    public function sendSay()
    {
        // 向任意uid的网站页面发送数据
//        $data['uid'] = input('post.uid');
//        $data['cmp_id'] = input('post.cmp_id');
//        $data['is_type'] = input('post.is_type',1);
//        $data['content'] = input('post.content');
//        $data['addtime'] = time();
        $data['fromid'] = input('post.fromid');
        $data['toid'] = input('post.toid');
        $data['type'] = input('post.type',1);
        $data['content'] = input('post.content');
        $data['time'] = time();
        $room = $data['fromid'].$data['toid'];
        $id = Db::name('chat_communication')->insertGetId($data);
        if($id){
//            if($data['is_type']==2){
//                $data['company_logo'] = db('company')->where('company_id',$data['cmp_id'])->value('company_logo');
//
//            }
//            $data['avatar'] = db('users')->where('id',$data['uid'])->value('avatar');
//            $data['addtime']=date('Y-m-d H:i', $data['addtime']);
            $message = [
                'type'=>'webmsg',
                'data'=>$data
            ];
           Gateway::sendToUid($data['toid'],json_encode($message));
           return json_encode($message);
//                if($data['is_type']==1){
//                    $username =db('users')->where('id',$data['uid'])->value('username');
//                    $time = db('chat')->where(array('uid'=>$data['uid'],'is_type'=>2,'cmp_id'=>$data['cmp_id']))->order('id desc')->value('addtime');
//                    $ids = db('users')->where('cp_id',$data['cmp_id'])->column('openid');
//                    if($time && time()-$time<500){
//                     return;
//                    }
//                }
//                if($data['is_type']==2){
//                    $username =db('company')->where('company_id',$data['cmp_id'])->value('company_name');
//                    $time = db('chat')->where(array('uid'=>$data['uid'],'is_type'=>1,'cmp_id'=>$data['cmp_id']))->order('id desc')->value('addtime');
//                    $ids = db('users')->where('id',$data['uid'])->column('openid');
//                    if($time && time()-$time<500){
//                        return;
//                    }
//                }
//                    $date = date('Y-m-d', strtotime('-6 days'));
//                    $tuser = db('wx_temp')
//                        ->field('id,openid,formid')
//                        ->where('status',0)
//                        ->where(['openid' => ['IN', $ids]])
//                        ->whereTime('create_time', '>=', $date)
//                        ->select();
//                    $key ='openid';
//                    $tuser = $this->second_array_unique_bykey($tuser,$key);
//                    //var_dump($tuser);die;
//                    $template_id='0v_e4LnzoAePl6owLfkS0ZPTjExDr-U9vjpvmw3CxL8';
//                    foreach ($tuser as $k){
//                        //var_dump($tuser);die;
//                        $res = $this->sendtpl($k['openid'],$template_id,$k['formid'],$username,$data['content']);
//                        $res = json_decode($res, true);
//
//                        if($res['errmsg'] == 'ok'){
//
//                            db('wx_temp')->where('id',$k['id'])->setField('status',1);
//                            echo $k['open_id'].'发送成功!'.$k['id'].'<br>';
//                        }else{
//                            // return $result = ['code'=>1,'msg'=>'发送失败!'];
//                            echo $k['open_id'].'发送成功!'.$k['id'].'<br>';
//                        }
//                    }

        }
    }
    /*
    *  获取聊天
    *  by:山水
    * 参数:uid openid
    *  Date:2019-4.26 10 :16
    */
    public function Sendchat(){
        if(request()->isPost()){
            $data['uid'] = input('post.uid');
            $data['cmp_id'] = input('post.cmp_id');
            $data['is_type'] = input('post.is_type',1);
            $data['content'] = input('post.content');
            $data['addtime'] = time();
            //$data = ['code'=>101,'msg'=>'ok'];
            // if(empty($openid)) return json_encode($data);
            $id=db('chat')->insertGetId($data);
 
            if($id){
                //$data['read1'] = 0;
                $data['addtime']=date('Y-m-d H:i', $data['addtime']);
                if($data['is_type']==2){
                //    $data['read2'] = 0;
                    $data['company_logo'] = db('company')->where('company_id',$data['cmp_id'])->value('company_logo');
                }
                //$data['read2'] = 0;
                $data['addtime']=date('Y-m-d H:i', $data['addtime']);
 
                return json_encode($data);
            }
 
 
        }
    }
    /*
    *  获取聊天
    *  by:山水
    * 参数:uid openid
    *  Date:2019-4.26 10 :16
    */
    public function sentWorkall(){
        if(request()->isPost()){
            $uid = input('post.uid');
 
            $ids=input('post.batchIds/a');
            $ids=implode(',',$ids);
            $data = ['code'=>101,'msg'=>'ok'];
            if(empty($uid)) return json_encode($data);
            $username=db('users')->where('id',$uid)->value('username');
            $list=db('work')
                ->field('work_name,cmp_id')
                ->whereIn('id',$ids)
                ->select();
            foreach($list as $k=>$v){
                $datas['content'] ='您好，我对'.$v['work_name']."职位很感兴趣，希望可以进一步了解，谢谢！";
                $datas['uid']=$uid;
                $datas['addtime']=time();
                $datas['cmp_id']=$v['cmp_id'];
                $id =db('chat')->insertGetId($datas);
                 if($id){
                 $ids = db('users')->where('cp_id',$v['cmp_id'])->column('openid');
                     $date = date('Y-m-d', strtotime('-6 days'));
                     $tuser = db('wx_temp')
                         ->field('id,openid,formid')
                         ->where('status',0)
                         ->where(['openid' => ['IN', $ids]])
                         ->whereTime('create_time', '>=', $date)
                         ->select();
                     $key ='openid';
                     $tuser = $this->second_array_unique_bykey($tuser,$key);
                     //var_dump($tuser);die;
                     $template_id='0v_e4LnzoAePl6owLfkS0ZPTjExDr-U9vjpvmw3CxL8';
                     foreach ($tuser as $k){
                         //var_dump($tuser);die;
                         $res = $this->sendtpl($k['openid'],$template_id,$k['formid'],$username,$datas['content']);
                         $res = json_decode($res, true);
 
                         if($res['errmsg'] == 'ok'){
 
                             db('wx_temp')->where('id',$k['id'])->setField('status',1);
                             echo $k['open_id'].'发送成功!'.$k['id'].'<br>';
                         }else{
                             // return $result = ['code'=>1,'msg'=>'发送失败!'];
                             echo $k['open_id'].'发送成功!'.$k['id'].'<br>';
                         }
                     }
                 }
            }
 
           // var_dump($list);die;

        }
    }
    /*
    *  获取聊天
    *  by:山水
    * 参数:uid openid
    *  Date:2019-4.26 10 :16
    */
    public function Mychat($uid,$cmp_id){
 
            $list = db('chat')->alias('ch')
                ->join(config('database.prefix').'company cp','ch.cmp_id= cp.company_id','left')
                ->join(config('database.prefix').'users u','ch.uid= u.id','left')
                ->field('ch.*,cp.company_id,cp.company_logo,u.avatar')
                ->where('uid',$uid)
                ->where('cmp_id',$cmp_id)
                ->select();
            foreach ($list as $k=>$v){
 
                $list[$k]['addtime'] = date('Y-m-d H:s',$v['addtime']);
                //  if()
            }
            return $list;
 
    }
    public  function second_array_unique_bykey($arr, $key){
        $tmp_arr = array();
        foreach($arr as $k => $v)
        {
            if(in_array($v[$key], $tmp_arr))  //搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
            {
                unset($arr[$k]); //销毁一个变量 如果$tmp_arr中已存在相同的值就删除该值
            }
            else {
                $tmp_arr[$k] = $v[$key]; //将不同的值放在该数组中保存
            }
        }
        //ksort($arr); //ksort函数对数组进行排序(保留原键值key) sort为不保留key值
        return $arr;
    }
    //模板推送
    public function sendtpl($openid,$template_id,$form_id,$keyword1,$keyword2){
        $accessToken = $this->getWxAccessToken();
        // var_dump($accessToken);die;
        $postData = array(
            "touser"        =>$openid,      //用户openid
            "template_id"   =>$template_id,  //模板消息ID
            "page"          =>'pages/member/index/index',
            "form_id"       =>$form_id,      //表单提交场景下，事件带上的 formId；支付场景下，为本次支付的 prepay_id
            "data"          =>array(
 
                'keyword1'  => array('value'=>$keyword1),
                'keyword2'  => array('value'=>$keyword2)
            ),
 
            'emphasis_keyword'=>''
        );
        //urldecode(json_encode($template))
        $postData =  json_encode($postData,true);
        // $res=$wxtlp->send_template_message(urldecode(json_encode($template)));
        $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token={$accessToken}";
        $rtn = $this->http_curl($url,$postData);
 
        return $rtn;
    }
    //Token
    public function getWxAccessToken(){
        $res= db('wx_user')->where('id',1)->find();
        //  $appid = 'wx43a225339e6e0525';
        //  $appsecret = 'cdb1ae0dcc026cc87160a97c9ba299da';
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$res['appid'].'&secret='.$res['appsecret'];
        $AccessToken = $this->http_curl($url);
        $AccessToken = json_decode($AccessToken , true);
        $AccessToken = $AccessToken['access_token'];
        return $AccessToken;
    }
    //curl
    public function http_curl($url, $data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}