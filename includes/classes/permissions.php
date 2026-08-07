<?php

class permissions extends db
{




function check_permission($module, $permission){

   if($module AND $permission){
    $datas = $this->mysqli->query("SELECT * from permissions where module='$module' and permission='$permission'");
    if($datas->num_rows){
        $values = $datas->fetch_object();
        $value = $values->id;
    }else{
        $value='';
        // $_SESSION['fal'] = 'Opps! You are not allowed to access this section!';
    }
   }else{
    @session_start();
    $_SESSION['fal'] = 'Invalid permissions!';
   }

    @session_start();
    $user=@$_SESSION['admin'];
    if($user)
    {
    $data = $this->mysqli->query("SELECT * FROM user where id = $user "); 
    if( $data->num_rows )
    {
        $userdata = $data->fetch_object();
        $perm = explode(", ",$userdata->permissions);
        if(in_array($value, $perm) OR $userdata->usertype=='root'){
            return 'true';
        }else{
           
            return 'false';
            
        }
    }else{
        $obj = new itways();
        $obj->logout ();
            }
}



}









function sidebar($module){

    if($module){
     $datas = $this->mysqli->query("SELECT * from permissions where module='$module' ");
     if($datas->num_rows){
        
        while ( $r = $datas->fetch_object() ){
            $row[] = $r;
        }
        $value = '';
        foreach($row as $values){
            $value .= $values->id.', ';
        }
        $permdb = explode(", ",$value);
         
     }else{
         //$value='';
     }
    }else{
     @session_start();
     $_SESSION['fal'] = 'Invalid permissions!';
    }
 
     @session_start();
     $user=@$_SESSION['admin'];
     if($user)
     {
     $data = $this->mysqli->query("SELECT * FROM user where id = $user "); 
     if( $data->num_rows )
     {
         $userdata = $data->fetch_object();
         $perm = explode(", ",$userdata->permissions);
         if(count(array_intersect($permdb, $perm))>=1 OR $userdata->usertype=='root'){
            
            return 'true';
         }else{
             
             return 'false';
         }
     }else{
         $obj = new itways();
         $obj->logout ();
             }
 }
 
 
 
 }





 function check_assign($user){

    if($user){
     $datas = $this->mysqli->query("SELECT * from permissions where module='$module' and permission='$permission'");
     if($datas->num_rows){
         $values = $datas->fetch_object();
         $value = $values->id;
     }else{
         $value='';
         // $_SESSION['fal'] = 'Opps! You are not allowed to access this section!';
     }
    }else{
     @session_start();
     $_SESSION['fal'] = 'Invalid permissions!';
    }
 
     @session_start();
     $user=@$_SESSION['admin'];
     if($user)
     {
     $data = $this->mysqli->query("SELECT * FROM user where id = $user "); 
     if( $data->num_rows )
     {
         $userdata = $data->fetch_object();
         $perm = explode(", ",$userdata->permissions);
         if(in_array($value, $perm) OR $userdata->usertype=='root'){
             return 'true';
         }else{
            
             return 'false';
             
         }
     }else{
         $obj = new itways();
         $obj->logout ();
             }
 }
 
 
 
 }







}







