# sf-express-api
顺丰速运仓储及顺丰冷运仓储接口

>本接口基于以下文档创建：

    企业服务平台接入技术规范_V3.6
    LSCM-V5.2-企业服务平台接入技术规范(仓储接口部分)

## 使用


```php
<?php 

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use aobeef\SFExpressAPI\BSP\OrderService;

// 此处的配置请改成真实数据，其默认运行在开发模式下。
$config = [
   'server' => "http://bspoisp.sit.sf-express.com:11080/",
   'server_ssl' => "https://bspoisp.sit.sf-express.com:11443/",
   'ssl' => false,
   'uri' => 'bsp-oisp/sfexpressService',
   'checkword' => 'j8DzkIFgmlomPt0aLuwU',
   'accesscode' => 'BSPdevelop'
];


//$service  = new OrderService($config);
// 可以不传$config，将运行在开发模式下。
$service  = new OrderService();

// 你自己ERP系统里的订单ID。
$orderid = 88888888;
// 收件方信息
$d_company = '罗湖火车站';
$d_contact = '小雷';
$d_tel = '13800000000';
$d_address = '罗湖火车站东区调度室';

// 其它可选参数
$data = array(
    // 寄件方信息
    'j_mobile'=>'13000000000',
    'j_province'=>'广东省',
    'j_city'=>'深圳',
    'j_county'=>'福田区',
    'j_address'=>'罗湖火车站东区调度室',
    
    'express_type'=>'1', // 快件产品类别
    'pay_method'=>'1', // 付款方式
    'parcel_quantity'=>'1', // 包裹数
    'cargo_length'=>'33', // 货物总长
    'cargo_width'=>'33', // 货物总宽
    'cargo_height'=>'33', // 货物总高
    'remark'=>'' // 备注
);

// 货物信息。可以有多个。 name为必填字段。
$Cargo = array(
    array( 'name'=>'LV背包', 'count'=>'3', 'unit'=>'只', 'weight'=>'', 'amount'=>'', 'currency'=>'', 'source_area'=>''),
    array('name'=>'LV手表', 'count'=>'3', 'unit'=>'块', 'weight'=>'', 'amount'=>'', 'currency'=>'', 'source_area'=>'')
);

// 下单
$ret = $service->Order($orderid , $d_company, $d_contact, $d_tel, $d_address, $data, $Cargo);

// 结果以数组形式返回。具体内容详见顺丰文档。
print_r($ret);

```
更多参数请参考顺丰文档。
