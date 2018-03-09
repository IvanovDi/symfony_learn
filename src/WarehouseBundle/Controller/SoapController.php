<?php

namespace WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class SoapController extends Controller
{
    public function indexAction()
    {
//        ini_set("soap.wsdl_cache_enabled", "0");

        $soapServer = new \SoapServer(__DIR__ . '/../Soap/SoapService.wsdl', [ 'trace' => true, 'cache_wsdl' => WSDL_CACHE_NONE ]);

        $soapServer->setObject($this->get('soap.service'));


        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');

        ob_start();
        $soapServer->handle();
        $response->setContent(ob_get_clean());

        return $response;
    }
}