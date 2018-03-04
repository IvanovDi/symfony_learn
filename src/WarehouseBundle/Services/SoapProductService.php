<?php

namespace WarehouseBundle\Services;

use Symfony\Component\HttpFoundation\Request;

class SoapProductService
{
    public function crateProduct($data)
    {
        file_put_contents('/home/dima/Documents/file.txt', print_r($data, true));
    }
}