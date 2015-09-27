<?php
require_once('src/TArray.php'); // TArray class

/*
* Factory method create TArray class
*/
function tArray(array $normalArray){
    return new TArray($normalArray);
}

function pre_dump(){
    echo "<pre>";
    foreach(func_get_args() as $value){
        var_dump($value);
    }
    echo "</pre>";
}

/**** TEST FILE ****/
$people = tArray(
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

function isDavid($person){
    return $person["name"] == "David";
}
function isMoses($person){
    return $person["name"] == "Moses";
}
function isTomer($person){
    return $person["name"] == "Tomer";
}

//$david = $people->filter("isDavid");
//foreach($david as $key => $person){
//    $david[$key] = $david[$key]->merge(array(
//        "hello" => "world"
//    ));
//}
$people[0]["o"] = [];
$people[0]["o"]["name"] = "Test";
pre_dump($people[0]);

//$tomer = $people->filter("isTomer");
//$moses = $people->filter("isMoses");
//$tomerAndMoses = $tomer->merge($moses->a());
for($i=0;$i<$people->length();$i++){
//    $people[$i]["name"] = "Tomer";
//    pre_dump($people[$i]);
}
