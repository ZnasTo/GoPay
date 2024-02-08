<?php 
//abstraktni trida, ktera definuje vlastnosti pro platebni brany
abstract class Payment 
{
    // konstanta pro rozliseni platebnich bran (mame jenom jednu zatim)
    const GOPAY = 1;
    // abstraktni metody
    abstract public function getStatus($parameters);
    abstract public function getIformation($parameters);
    abstract public function getUrl($parameters);

    
}