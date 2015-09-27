<?php
class TArray implements ArrayAccess, Iterator {

    private $position = 0;
    private $container = array();
    private $referenceFunctions = array("array_push","array_pop","array_shift","array_unshift");

    public function __construct(array $array) {
        $this->container = $array;
        $this->position = key($array);
    }

    public function offsetSet($offset, $value) {
        $value = is_array($value) ? self::createInstance($value):$value;
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset) {
        if(!isset($this->container[$offset])){
            return null;
        }
        if(is_array($this->container[$offset])){
            $this->offsetSet($offset,self::createInstance($this->container[$offset]));
        }
        return $this->container[$offset];
    }

    function rewind() {
        $this->position = key($this->container);
    }

    function current() {
        return $this->container[$this->position];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->container[$this->position]);
    }

    public function __call($func,$args){
        $callFunc = "array_".$func;
        if(!is_callable($callFunc)){
            throw new Exception("{$callFunc} method not found");
        }
        $callByReference = in_array($callFunc,$this->referenceFunctions);
        $container = $this->container;
        if($callFunc == "array_map"){
            $args = array_merge($args,array(&$container));
        }else{
            $args = array_merge(array(&$container),$args);
        }

        $results = call_user_func_array($callFunc, $args);

        if(!$callByReference){
            $results = is_array($results) ? new self($results):$results;
        }else{
            $this->container = $container;
            $results = $this;
        }

        return $results;
    }

    public function each($callback){
        if(!is_callable($callback)){
            throw new Exception('missing callback function');
        }
        foreach($this->container as $key => $value){
            call_user_func_array($callback,array($key,$value));
        }
        return $this;
    }

    public function a(){
        return $this->container;
    }

    public function length(){
        return count($this->container);
    }

    public static function createInstance($array){
        if(!is_array($array)){
            return false;
        }

        return new self($array);
    }

}
