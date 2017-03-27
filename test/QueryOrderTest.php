<?php 
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST');
header('Content-Type:application/json');
require_once __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload

use Aobeef\SFExpressAPI\WS\SaleOrderQueryRequest;

// 可以不传$config，将运行在开发模式下。
$service  = new SaleOrderQueryRequest();

// 你自己ERP系统里的订单ID。
$orderid = '58c204678ac2470720f31874';

$ret = $service->OrderRequest($orderid);

// 结果以数组形式返回。具体内容详见顺丰文档。
print_r($ret);
echo json_encode($ret);