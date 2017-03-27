<?php namespace Aobeef\SFExpressAPI\Core;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class AbstractWS
{
    protected $config = [
        'server' => "http://scs-drp-core-out.sit.sf-express.com:3580/",
        'uri' => 'BspToOms',
        'checkword' => 'QHCS-01',
        'company' => 'QHCS-01',
        'warehouse' => '010VB'
    ];

    private $SERVICE = array(
        'Aobeef\SFExpressAPI\WS\SaleOrderRequest'                          => 'wmsSailOrderRequest',
        'Aobeef\SFExpressAPI\WS\CancelSaleOrderRequest'                    => 'wmsCancelSailOrderRequest',
        'Aobeef\SFExpressAPI\WS\SaleOrderQueryRequest'                     => 'wmsSailOrderQueryRequest',
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

    public function ApiPost($xml) {
        try {
            $client =  new Client(['base_uri' => $this->config['server'], 'timeout'  => 2.0]);

            $header['charset'] = 'UTF-8';
            $header['Content-Type'] = 'text/xml';
            $response = $client->post(
                $this->config['uri'],
                ['body' => $xml]
            );
            $code = $response->getStatusCode();
            $reason = $response->getReasonPhrase();
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
        $xml = '<'.$this->getServiceName(get_called_class()).'>' .
            '<checkword>'.$this->config['checkword'].'</checkword>' .
            $bodyData .
            '</'.$this->getServiceName(get_called_class()).'>';
        return $xml;
    }
}