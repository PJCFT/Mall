<?php
header("content-type:text/html;charset=utf-8");//处理浏览器乱码问题
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/5
 * Time: 9:13
 */
include_once './lib/fun.php';
if($login = checkVisitor_Login()){
    $visitor = $_SESSION['visitor'];
}
$visitor = $_SESSION['visitor'];
$userId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : '';

//如果id不存在 跳转到首页
if(!$userId)
{
    msg(2,'该商家不存在','../index.php');
}

$con = mysqlInit($host, $Username, $Password, $dbName);
if(!$con){
    echo mysql_errno();
    exit;
}

$sql = "select * from `nt_user` where `user_id`='{$userId}'";
$obj = mysql_query($sql);
$user= mysql_fetch_assoc($obj);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mall|首页</title>
    <link rel="stylesheet" type="text/css" href="../static/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="../static/css/index.css"/>
</head>
<body>
<div class="header">
    <div class="logo f1">
        <a href="../index.php"><img src="../static/image/logo.png"></a>
    </div>

    <div class="auth fr">
        <ul>
            <?php if ($login):?>
                <li><span>您好： <?php echo $visitor['visitor_name'];?></span></li>
                <li><a href="./pass.php?id=<?php echo $visitor['visitor_id'];?>">密码修改</a></li>
                <li><a href="./collection_list.php">我的收藏</a> </li>
                <li><a href="./log_out.php">退出</a></li>
            <?php else: ?>
                <li><a href="./login.php">登录</a></li>
                <li><a href="./register.php">注册</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<div class="content">
    <div class="banner">
        <img class="banner-img" src="../static/image/welcome.png" width="732px" height="372" alt="图片描述">
    </div>
    <div class="path"></font><font style="color:red; margin:0 5px;font-size: 18px;"></div>
    <h2>下面为商家的相关信息，请根据信息和商家联系</h2>
    <h2>商家名：<?php echo $user['user_name']?></h2>
    <h2>商家地址：<?php echo $user['user_address']?></h2>
    <h2>商家联系电话：<?php echo $user['user_phone']?></h2>
</div>

<div class="footer">
    <p><span>Mall</span>©2018 POWERED BY PJC</p>
</div>
</body>
</html>
