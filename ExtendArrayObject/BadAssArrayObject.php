<?php
error_reporting(E_ALL | E_STRICT);

class BadAssArrayObject implements ArrayAccess, Iterator {

    private $position = 0;
    private $container = array();
    private $referenceFunctions = array("array_push","array_pop","array_shift","array_unshift");

    public function __construct(array $array) {
        $this->container = $array;
        $this->position = key($array);
    }

    public function offsetSet($offset, $value) {
        if(is_a($value, __CLASS__)){
            $value = $value->a();
        }
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
        if(isset($this->container[$offset])){
            return is_array($this->container[$offset]) ? new self($this->container[$offset]):$this->container[$offset];
        }
        return null;
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
            echo "Shit";
        }
        $callByReference = in_array($callFunc,$this->referenceFunctions);
        $container = $this->container;
        if($callFunc == "array_map"){
            array_push($args,$container);
        }else{
            array_unshift($args,$container);
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
            echo "Shit";
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

}

$people = _array(
    array(
        array(
            "name" => "Tomer",
            "age" => 25
        ),
        array(
            "name" => "David",
            "age" => 23
        ),
        array(
            "name" => "Moses",
            "age" => 999
        )
    )
);

$david = $people->filter(function($el){
    return $el["name"] == "David";
});

function pre_dump($s){
    echo "<pre>";
    var_dump($s);
    echo "</pre>";
}

function _array(array $normalArray){
    return new BadAssArrayObject($normalArray);
}