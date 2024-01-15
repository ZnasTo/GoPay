<?php 
abstract class Payment 
{
    abstract public function getStatus($statusID);
    abstract public function getIformation($statusID);
    abstract public function getUrl($parameters);

    
}