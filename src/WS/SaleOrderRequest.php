<?php namespace Aobeef\SFExpressAPI\WS;
use Aobeef\SFExpressAPI\Core\AbstractWS;
use Aobeef\SFExpressAPI\Support\Auth;
use Aobeef\SFExpressAPI\Support\XML;

/**
 * 出库单接口提供给客户系统向顺丰仓储系统发送出库单数据，以便顺丰仓储根据出库单数据安排出货流程。
 */
class SaleOrderRequest extends AbstractWS
{

    /**
     * Order Request
     * @param $erp_order 订单号码
     * @param $ship_to_attention_to 收件人
     * @param $ship_to_province 收件人所在 省
     * @param $ship_to_city 收件人所在 市
     * @param $ship_to_area 收件人所在 区/县
     * @param $ship_to_address 收件人详细 地址
     * @param $ship_to_phone_num 收件人手机
     * @param $items
     * @return array
     */
    public function OrderRequest($erp_order, $ship_to_attention_to, $ship_to_province, $ship_to_city, $ship_to_area, $ship_to_address, $ship_to_phone_num, $items) {
        $date = new \DateTime();
        $datetimes = $date->format('Y-m-d H:i:s');
        $itemRequest = '<header>';
        $itemRequest .= '<company>'.$this->config['company'].'</company>';
        $itemRequest .= '<warehouse>'.$this->config['warehouse'].'</warehouse>';
        $itemRequest .= '<erp_order>'.$erp_order.'</erp_order>';
        $itemRequest .= '<order_type>销售订单</order_type>';
        $itemRequest .= '<order_date>'.$datetimes.'</order_date>';
        $itemRequest .= '<ship_to_name></ship_to_name>';
        $itemRequest .= '<ship_to_attention_to>'.$ship_to_attention_to.'</ship_to_attention_to>';
        $itemRequest .= '<ship_to_country></ship_to_country>';
        $itemRequest .= '<ship_to_province>'.$ship_to_province.'</ship_to_province>';
        $itemRequest .= '<ship_to_city>'.$ship_to_city.'</ship_to_city>';
        $itemRequest .= '<ship_to_area>'.$ship_to_area.'</ship_to_area>';
        $itemRequest .= '<ship_to_address>'.$ship_to_address.'</ship_to_address>';
        $itemRequest .= '<ship_to_postal_code></ship_to_postal_code>';
        $itemRequest .= '<ship_to_phone_num>'.$ship_to_phone_num.'</ship_to_phone_num>';
        $itemRequest .= '</header><detailList>'.$this->Items($items).'</detailList>';
        $xml = $this->buildXml($itemRequest);
        $data = $this->ApiPost($xml);
        return $this->OrderResponse($data);
    }

    /**
     * 生成货物信息
     * @param $items
     * @return array|\GuzzleHttp\Stream\StreamInterface|null|\SimpleXMLElement|string
     */
    private static function Items($items) {
        $data = '';
        if (count($items) > 0) {
            $count = 1;
            foreach ($items as $item) {
                $root = '<item>';
                $root .= '<erp_order_line_num>'.$count.'</erp_order_line_num>';
                $root .= '<item>'.$item['item'].'</item>';
                $root .= '<item_name>'.$item['item_name'].'</item_name>';
                $root .= '<uom>'.$item['uom'].'</uom>';
                $root .= '<qty>'.$item['qty'].'</qty>';
                $root .= '</item>';
                $data .= $root;
                $count ++;
            }
        }
        return $data;
    }

    public function OrderResponse($data) {

        $ret = $this->ret;
        $xml = @simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
        if ($xml){
            $ret = array();
            $ret = XML::parse($data);
        }
        return $ret;
    }
}