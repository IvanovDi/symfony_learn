<?php

namespace WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class SoapController extends Controller
{
    public function indexAction(Request $request)
    {
        $soapServer = new \SoapServer(__DIR__ . '/../Soap/SoapService.wsdl', ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);

        $soapServer->setObject($this->get('soap.product.service'));


        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');

        ob_start();
        $soapServer->handle($request);
        $response->setContent(ob_get_clean());

        return $response;
    }
}