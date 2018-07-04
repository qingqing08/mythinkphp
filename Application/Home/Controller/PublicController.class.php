<?php
namespace Home\Controller;
use Think\Controller;
class PublicController extends Controller{
    public function login(){
        $info = M("Students")->where("login_status=1")->find();
        if(empty($info)){
            $this->display();
        } else{
            $list = M("Students")->order("tickets desc")->select();
            $this->assign("info",$info);
            $this->assign("list",$list);
            $this->display("index");
        }
        
    }
    
    public function index(){
        $info = M("Students")->where("login_status=1")->find();
        if(empty($info)){
            $this->display("login");
        } else{
            $list = M("Students")->order("tickets desc")->select();
            $this->assign("info",$info);
            $this->assign("list",$list);
            $this->display();
        }
    }
    
    public function login_do(){
        $username = $_POST["username"];
        $password = $_POST["password"];
        
        $where = "username = '{$username}'";
        $info = M("Students")->where($where)->find();
        
        if(empty($info)){
            $this->error("该用户不存在");
        }
        
        if(empty($username)){
            $this->error("用户名不能为空");
        }
        
        if(empty($password)){
            $this->error("密码不能为空");
        }
        
        if(md5($password) != $info["password"]){
            $this->error("密码不匹配");
        }
        
        $data = array(
            "login_status"  =>  1,
        );
        $result = M("Students")->where($where)->save($data);
        if($result == false){
            $this->error();
        } else {
            $this->success("登录成功",U("Public/index"));
        }
        
    }
    
    public function quit(){
        $id = $_GET["id"];
        $data = array(
            "login_status"  =>  0,
        );
        
        $where = "id = '{$id}'";
        $result = M("Students")->where($where)->save($data);
        if($result == false){
            $this->error();
        } else {
            $this->success("退出成功",U("Public/login"));
        }
    }
}
