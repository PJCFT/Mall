<?php
include_once '../lib/fun.php';
header("content-type:text/html;charset=utf-8");//处理浏览器乱码问题
if(!checkLogin()){
    msg(2,'请登录！','../login.php');
}

if(!empty($_POST['user_name'])){
    //数据库连接
    $con = mysqlInit($host, $Username, $Password, $dbName);
    if(!$con){
        echo mysql_errno();
        exit;
    }
    $visitorname = mysql_real_escape_string(trim($_POST['user_name']));
    $visitorpass = mysql_real_escape_string(trim($_POST['user_pass']));
    $visitorrepass = mysql_real_escape_string(trim($_POST['user_repass']));


    if(!$visitorname){
        msg(2, '普通用户名称不能为空，请重新输入！');
    }
    if(!$visitorpass){
        msg(2, '普通用户密码不能为空！，请重新输入！');
    }
    if($visitorpass != $visitorrepass){
        msg(2, '两次密码输入不正确！，请重新输入！');
    }

    //判断用户是否在数据表中存在
    $sql = "SELECT COUNT(  `visitor_id` ) as total FROM  `nt_visitor` WHERE  `visitor_name` =  '{$visitorname}'";
    $obj = mysql_query($sql);
    $result = mysql_fetch_assoc($obj);

    //验证用户已存在数据库
    if(isset($result['total']) && $result['total'] > 0){
        msg(2,'用户名已存在，请重新输入！');
    }
    //密码加密处理
    $password  = createPassword($visitorpass);
    unset($obj, $result, $sql);
    //插入数据
    $sql = "INSERT `nt_visitor`(`visitor_name`,`visitor_password`,`create_time`,`update_time`) values('{$visitorname}','{$password}','{$_SERVER['REQUEST_TIME']}','{$_SERVER['REQUEST_TIME']}')";
    $obj = mysql_query($sql);
    if($obj){
        msg(1,'普通用户添加成功！','list.php');
//        $userId = mysql_insert_id();//插入成功的主键id
//        echo sprintf('恭喜您，用户名是：%s，用户id：%s',$username,$userId);
//        exit;
    }else{
        msg(2,mysql_errno());
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../style/css/ch-ui.admin.css">
	<link rel="stylesheet" href="../style/font/css/font-awesome.min.css">
</head>
<body>
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="../info.php">首页</a> &raquo; <a href="list.php">普通用户管理</a> &raquo; 添加普通用户
    </div>
    <!--面包屑导航 结束-->

	<!--结果集标题与导航组件 开始-->
	<div class="result_wrap">
        <div class="result_title">
            <h3>添加普通用户</h3>
        </div>
    </div>
    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        <form action="add.php" method="post" name="register" id="register-form">
            <table class="add_tab">
                <tbody>
                <tr>
                    <th><i class="require">*</i>普通用户名称：</th>
                    <td>
                        <input type="text" name="user_name" placeholder="普通用户名称" id="user_name">
                        <span><i class="fa fa-exclamation-circle yellow"></i>普通用户名称必须填写</span>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>普通用户密码：</th>
                    <td>
                        <input type="password" name="user_pass" placeholder="普通用户密码" id="user_pass">
                        <span><i class="fa fa-exclamation-circle yellow"></i>普通用户密码必须填写</span>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>确认密码：</th>
                    <td>
                        <input type="password" name="user_repass" placeholder="确认密码" id="user_repass">
                        <span><i class="fa fa-exclamation-circle yellow"></i>再次输入普通用户密码</span>
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <input type="submit" value="提交">
                        <input type="button" class="back" onclick="history.go(-1)" value="返回">
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>
</body>
<script src="../style/js/jquery-1.10.2.min.js"></script>
<script src="../style/js/layer/layer.js"></script>
<script>
    $(function () {
        $('#register-form').submit(function () {
            var username = $('#user_name').val(),
                password = $('#user_pass').val(),
                repassword = $('#user_repass').val();
                userphone = $('#user_phone').val();
            if (username == '' || username.length <= 0) {
                layer.tips('普通用户名称不能为空', '#user_name', {time: 2000, tips: 2});
                $('#user_name').focus();
                return false;
            }
            if (password == '' || password.length <= 0) {
                layer.tips('普通用户密码不能为空', '#user_pass', {time: 2000, tips: 2});
                $('#user_pass').focus();
                return false;
            }
            if(password.length <= 5 || password.length > 30){
                layer.tips('普通用户密码过短，密码应在6到30位之间！', '#user_pass', {time:2000, tips:2});
                $('#user_pass').focus();
                return false;
            }

            if (repassword == '' || repassword.length <= 0 || (password != repassword)) {
                layer.tips('两次密码输入不一致', '#user_repass', {time: 2000, tips: 2});
                $('#user_repass').focus();
                return false;
            }

            return true;
        })
    })
</script>
</html>