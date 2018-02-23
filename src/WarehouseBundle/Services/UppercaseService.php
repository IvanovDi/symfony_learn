<?php

namespace WarehouseBundle\Services;

class UppercaseService
{
    public function toUppercase($string)
    {
        return mb_strtoupper($string);
    }
}