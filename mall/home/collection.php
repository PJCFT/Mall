<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/22
 * Time: 8:21
 */
include_once './lib/fun.php';
if($login = checkVisitor_Login()){
    $visitor = $_SESSION['visitor'];
}
$visitorId = $visitor['visitor_id'];
//获取商品id
$goodId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : '';

//如果id不存在 跳转到首页
if(!$goodId)
{
    msg(2,'该商品不存在','../index.php');
}
$con = mysqlInit($host, $Username, $Password, $dbName);
if(!$con){
    echo mysql_errno();
    exit;
}
//查询相应商品
$sql = "SELECT * FROM `nt_goods` WHERE `id` = '{$goodId}'";
$obj = mysql_query($sql);
$result = mysql_fetch_assoc($obj);
$id = $result['id'];
$name = $result['name'];
$price = $result['price'];
$pic = $result['pic'];
$des = $result['des'];
$content = $result['content'];
$user_id = $result['user_id'];
$create_time = $result['create_time'];
$update_time = $result['update_time'];
$view = $result['view'];

//判断收藏是否在数据表中存在
$sql = "SELECT COUNT(  `id` ) as total FROM  `nt_collections` WHERE  `id` =  '{$goodId}'";
$obj = mysql_query($sql);
$result = mysql_fetch_assoc($obj);

//验证用户已存在数据库
if(isset($result['total']) && $result['total'] > 0){
    msg(2,'该商品已收藏！','collection_list.php');
}
unset($sql,$obj,$result);

$sql = "INSERT `nt_collections`(`visitor_id`,`id`,`name`,`price`,`pic`,`des`,`content`,`user_id`,`create_time`,`update_time`,`view`) values('{$visitorId}','{$id}','{$name}','{$price}','{$pic}','{$des}','{$content}','{$user_id}','{$create_time}','{$update_time}','{$view}')";
$obj = mysql_query($sql);
if($obj){
    msg(1,"收藏成功",'collection_list.php');
}else{
    msg(2,mysql_error());
}