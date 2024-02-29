<?php 
// Abstraktní třída definující vlastnosti pro platební brány
abstract class Payment 
{
    // Konstanta pro rozlišení platebních bran (momentálně máme pouze GOPAY)
    const GOPAY = 1;
    
    // Abstraktní metody
    abstract public function getStatus($parameters);
    abstract public function getInformation($parameters);
    abstract public function getUrl($parameters);

    
}