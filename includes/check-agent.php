<?php 
require_once('config.php');
$boj->check_session();


if(isset($_POST["mail"])) {
    $value = trim($_POST["mail"]);

$client = $boj->count("select agent_mail from agent where agent_mail = '$value' AND agent_mail!='' "); 


if($client>0){
    $result ="This email is already exists in the database!";
}else{
    $result = "<i class='fa fa-check'></i> Ok!";
}
    
    echo $result;
}

if(isset($_POST["contact"])) {
    $value = trim($_POST["contact"]);

$client = $boj->count("select agent_contact from agent where agent_contact = '$value'  "); 


if($client>0){
    $result ="This number is already exists!";
}else{
    if(strlen($value)=='10'){
    $result = "<i class='fa fa-check'></i> Ok!";
    }else{
        $result="";
    }
}
    
    echo $result;
}