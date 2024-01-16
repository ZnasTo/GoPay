<?php 
abstract class Payment 
{
    abstract public function getStatus($parameters);
    abstract public function getIformation($parameters);
    abstract public function getUrl($parameters);

    
}