<?php namespace Aobeef\SFExpressAPI\Core;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class AbstractOMS
{
    protected $config = [
        'server' => "http://bsp.sit.sf-express.com:8080/",
        'uri' => 'bsp-wms/OmsCommons',
        'checkword' => 'j8DzkIFgmlomPt0aLuwU',
        'accesscode' => 'BSPdevelop',
        'companycode ' => ''
    ];

    private $SERVICE = array(
        'Aobeef\SFExpressAPI\OMS\ItemService'                           => 'ITEM_SERVICE',
        'Aobeef\SFExpressAPI\OMS\ItemQueryService'                      => 'ITEM_QUERY_SERVICE',
        'Aobeef\SFExpressAPI\OMS\ItemChangePushService'                 => 'ITEM_CHANGE_PUSH_SERVICE',
        'Aobeef\SFExpressAPI\OMS\BOMService'                            => 'BOM_SERVICE',
        'Aobeef\SFExpressAPI\OMS\VendorService'                         => 'VENDOR_SERVICE',
        'Aobeef\SFExpressAPI\OMS\PurchaseOrderService'                  => 'PURCHASE_ORDER_SERVICE',
        'Aobeef\SFExpressAPI\OMS\CancelPurchaseOrderService'            => 'CANCEL_PURCHASE_ORDER_SERVICE',
        'Aobeef\SFExpressAPI\OMS\PurchaseOrderInboundPushService'       => 'PURCHASE_ORDER_INBOUND_PUSH_SERVICE',
        'Aobeef\SFExpressAPI\OMS\PurchaseOrderInboundQueryService'      => 'PURCHASE_ORDER_INBOUND_QUERY_SERVICE',
        'Aobeef\SFExpressAPI\OMS\SaleOrderService'                      => 'SALE_ORDER_SERVICE',
        'Aobeef\SFExpressAPI\OMS\CancelSaleOrderService'                => 'CANCEL_SALE_ORDER_SERVICE',
        'Aobeef\SFExpressAPI\OMS\SaleOrderOutboundDetailPushService'    => 'SALE_ORDER_OUTBOUND_DETAIL_PUSH_SERVICE',
        'Aobeef\SFExpressAPI\OMS\SaleOrderOutboundDetailQueryService'   => 'SALE_ORDER_OUTBOUND_DETAIL_QUERY_SERVICE',
        'Aobeef\SFExpressAPI\OMS\SaleOrderStatusPushService'            => 'SALE_ORDER_STATUS_PUSH_SERVICE',
        'Aobeef\SFExpressAPI\OMS\SaleOrderStatusQueryService'           => 'SALE_ORDER_STATUS_QUERY_SERVICE',
        'Aobeef\SFExpressAPI\OMS\AsynSaleOrderService'                  => 'ASYN_SALE_ORDER_SERVICE',
        'Aobeef\SFExpressAPI\OMS\AsynSaleOrderConfirmPushService'       => 'ASYN_SALE_ORDER_CONFIRM_PUSH_SERVICE',
        'Aobeef\SFExpressAPI\OMS\AsynSaleOrderConfirmQueryService'      => 'ASYN_SALE_ORDER_CONFIRM_QUERY_SERVICE',
        'Aobeef\SFExpressAPI\OMS\PartialShipmentService'                => 'PARTIAL_SHIPMENT_SERVICE',
        'Aobeef\SFExpressAPI\OMS\AllocationOrderService'                => 'ALLOCATION_ORDER_SERVICE',
        'Aobeef\SFExpressAPI\OMS\RTInventoryPushService'                => 'RT_INVENTORY_PUSH_SERVICE',
        'Aobeef\SFExpressAPI\OMS\RTInventoryQueryService'               => 'RT_INVENTORY_QUERY_SERVICE',
        'Aobeef\SFExpressAPI\OMS\InventoryChangeService'                => 'INVENTORY_CHANGE_SERVICE',
        'Aobeef\SFExpressAPI\OMS\InventoryBalanceService'               => 'INVENTORY_BALANCE_SERVICE',
        'Aobeef\SFExpressAPI\OMS\InventoryBalancePageQueryService'      => 'INVENTORY_BALANCE_PAGE_QUERY_SERVICE',
        'Aobeef\SFExpressAPI\OMS\ReceiptSerialNumberService'            => 'RECEIPT_SERIAL_NUMBER_SERVICE',
        'Aobeef\SFExpressAPI\OMS\CycleActionQueryService'               => 'CYCLE_ACTION_QUERY_SERVICE',
        'Aobeef\SFExpressAPI\OMS\OrderInvoiceService'                   => 'ORDER_INVOICE_SERVICE',
        'Aobeef\SFExpressAPI\OMS\SerialNumberPushService'               => 'SERIAL_NUMBER_PUSH_SERVICE',
        'Aobeef\SFExpressAPI\OMS\InventorySNQueryService'               => 'INVENTORY_SN_QUERY_SERVICE',
        'Aobeef\SFExpressAPI\OMS\CycleCountRequestQueryService'         => 'CYCLE_COUNT_REQUEST_QUERY_SERVICE',
        'Aobeef\SFExpressAPI\OMS\CycleCountRequestPushService'          => 'CYCLE_COUNT_REQUEST_PUSH_SERVICE',
        'Aobeef\SFExpressAPI\OMS\WmsSaleOrderWavePushService'           => 'WMS_SALE_ORDER_WAVE_PUSH_SERVICE',
        'Aobeef\SFExpressAPI\OMS\InventoryOccupancyPushService'         => 'INVENTORY_OCCUPANCY_PUSH_SERVICE',
    );

    protected $ret = array(
        'head' => "ERR",
        'message' => '系统错误',
        'code' => -1
    );

    public function __construct($params = null)
    {
        if (null != $params) {
            $this->config = array_merge($this->config, $params);
        }
    }

    public function ApiPost($query=array(), $header=array()) {
        try {
            $client =  new Client(['base_uri' => $this->config['server']]);

            $header['charset'] = 'UTF-8';
            $header['Content-Type'] = 'application/x-www-form-urlencoded';

            // 数据需要以form_params提交，不然传过去时会附加多余的数据。导致签名验证失败。
            $response = $client->post(
                $this->config['uri'],
                array(
                    'form_params' => $query,
                    'headers' => $header,
                    'verify' => false
                )
            );
            $body = $response->getBody();
            $contents = $body->getContents();
            return $contents;
        } catch(RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse()->getBody()->getContents();
            } else {
                return $e->getMessage();
            }
        }
    }

    /**
     * get request service name.
     * @param null $class
     * @return mixed
     */
    public function getServiceName($class=null) {
        if(empty($class)){
            return $this->SERVICE[get_called_class()];
        }
        return $this->SERVICE[$class];
    }

    /**
     * build full xml.
     * @param $bodyData
     * @return string
     */
    public function buildXml($bodyData){
        $xml = '<Request service="'.$this->getServiceName(get_called_class()).'" lang="zh-CN">' .
            '<Head>' .
            '<AccessCode>'.$this->config['accesscode'].'</AccessCode>' .
            '<Checkword>'.$this->config['checkword'].'</Checkword>' .
            '</Head>'.
            '<Body>' . $bodyData . '</Body>' .
            '</Request>';
        return $xml;
    }
}