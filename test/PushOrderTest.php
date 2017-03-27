<?php 
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST');
header('Content-Type:application/json');
require_once __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload

use Aobeef\SFExpressAPI\WS\SaleOrderRequest;

// 可以不传$config，将运行在开发模式下。
$service  = new SaleOrderRequest();

// 你自己ERP系统里的订单ID。
$erp_order = '58c204678ac2470720f31874';
// 收件方信息
$ship_to_attention_to = '张三';
$ship_to_province = '';
$ship_to_city = '上海';
$ship_to_area = '静安';
$ship_to_address = '武定路';
$ship_to_phone_num = '13791000000';

// 货物信息。可以有多个。 name为必填字段。
$items = array(
    array( 'item'=>'000256', 'item_name'=>'西冷', 'uom'=>'份', 'qty'=>'3')
);

$ret = $service->OrderRequest($erp_order , $ship_to_attention_to, $ship_to_province, $ship_to_city, $ship_to_area, $ship_to_address, $ship_to_phone_num, $items);

// 结果以数组形式返回。具体内容详见顺丰文档。
print_r($ret);
echo json_encode($ret);