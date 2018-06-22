<?php
/**
 * Created by PhpStorm.
 * User: PJC
 * Date: 2018/6/22
 * Time: 19:44
 */
include_once './lib/fun.php';
if($login = checkVisitor_Login()){
    $visitor = $_SESSION['visitor'];
}
$visitorId = $visitor['visitor_id'];
$goodsId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : '';
//如果id不存在 跳转到商品列表
if(!$goodsId)
{
    msg(2,'参数非法','../index.php');
}
//连接数据库
$con = mysqlInit($host, $Username, $Password, $dbName);
if(!$con){
    echo mysql_errno();
    exit;
}
$sql = "DELETE FROM `nt_collections` WHERE `id` = {$goodsId}";
$obj = mysql_query($sql);
if ($obj){
    msg(1,'取消收藏成功！','../index.php');
}else{
    msg(2,'操作失败！',mysql_error());
}