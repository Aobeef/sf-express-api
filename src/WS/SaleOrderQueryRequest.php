<?php namespace Aobeef\SFExpressAPI\WS;
use Aobeef\SFExpressAPI\Core\AbstractWS;
use Aobeef\SFExpressAPI\Support\Auth;
use Aobeef\SFExpressAPI\Support\XML;

/**
 * 此接口供使用顺丰仓储的客户系统查询出库明细信息。
 */
class SaleOrderQueryRequest extends AbstractWS
{

    /**
     * Order Request
     * @param $orderid 订单号码
     * @return array
     */
    public function OrderRequest($orderid) {
        $date = date_create();
        $itemRequest .= '<company>'.$this->config['company'].'</company>';
        $itemRequest .= '<warehouse>'.$this->config['warehouse'].'</warehouse>';
        $itemRequest .= '<orderid>'.$orderid.'</orderid>';
        $xml = $this->buildXml($itemRequest);
        $data = $this->ApiPost($xml);
        return $this->OrderResponse($data);
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