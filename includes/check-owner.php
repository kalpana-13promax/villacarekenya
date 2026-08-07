<?php 
require_once('config.php');
$boj->check_session();


if(isset($_POST["mail"])) {
    $value = trim($_POST["mail"]);

$client = $boj->count("select mail from owner where mail = '$value' AND mail!='' "); 


if($client>0){
    $result ="This email is already exists in the database!";
}else{
    $result = "<i class='fa fa-check'></i>";
}
    
    echo $result;
}

if(isset($_POST["contact"])) {
    $value = trim($_POST["contact"]);

$client = $boj->count("select contact, alternate_contact from owner where contact = '$value' OR alternate_contact = '$value' "); 


if($client>0){
    $result ="This number is already exists!";
}else{
    if(strlen($value)=='10'){
    $result = "<i class='fa fa-check'></i>";
    }else{
        $result="";
    }
}
    
    echo $result;
}