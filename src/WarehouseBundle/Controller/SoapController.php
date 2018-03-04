<?php

namespace WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SoapController extends Controller
{
    public function indexAction(Request $request)
    {
//        $soapServer = new \SoapServer("http://{$_SERVER['HTTP_HOST']}/SoapService.wsdl", ['soap_version' => SOAP_1_2]);
//        $soapServer->setObject($this->get('soap.product.service'));

        file_put_contents('/home/dima/Documents/file.txt', print_r('hello world', true));
        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');

        ob_start();
//        $soapServer->handle($request);
        $response->setContent(ob_get_clean());

        return $response;
    }
}