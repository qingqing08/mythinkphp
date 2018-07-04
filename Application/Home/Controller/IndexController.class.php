<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover,{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }
    
    public function add(){
        $username = $_GET["username"];
        $this->assign("username",$username);
        $this->display();
    }
        
    public function add_do(){
        $username = $_POST["username"];
        $password = $_POST["password"];
        $user = $_POST["user"];
        
        if($user != 'pengqingqing'){
            $this->error("您没有权限添加用户",U("Public/index"));
        }
        
        if(empty($username)){
            $this->error("用户名不能为空");
        }
        if(empty($password)){
            $this->error("密码不能为空");
        }
        $data = array(
            "username"  =>  $username,
            "password"  => md5($password),
            "create_time"   => date("Y-m-d H:i:s", time())
        );
        
        $result = M("Students")->add($data);
        if($result == false){
            $this->error("添加失败");
        } else {
            $this->success("添加成功",U("Public/index"));
        }
    }
    
    public function vote(){
        $user1 = $_GET["user1"];
        $user2 = $_GET["user2"];
        
        $where1 = "username = '$user1'";
        $user1info = M("Students")->where($where1)->find();
        if($user1info["available_tickets"] >= 3){
            $this->error("无可用次数");
        }
        
        $where = "user1='$user1' and user2='$user2'";
        $list = M("Vote")->where($where)->find();
        if(!empty($list)){
            $this->error("不能给相同的童鞋投票，请换位童鞋");
        }
        
        $data = array(
            "user1" =>  $user1,
            "user2" =>  $user2,
            "create_time"   => date("Y-m-d H:i:s",time()),
        );
        
        $where2 = "username = '$user2'";
        $user2info = M("Students")->where($where2)->find();
        $user1_data = array(
            "available_tickets" =>  $user1info["available_tickets"]+1,
        );
        $user2_data = array(
            "tickets" =>    $user2info["tickets"]+1,
        );
        M("Students")->where($where1)->save($user1_data);
        M("Students")->where($where2)->save($user2_data);
        $result = M("Vote")->add($data);
        if($result == false){
            $this->error("网络开小差了，投票失败");
        } else {
            $this->success("投票成功",U("Public/index"));
        }
        
    }
}