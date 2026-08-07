<?php 
require_once('config.php');
$boj->check_session();

$property_id = $_POST['property_id'];


$data = $boj->getQuery("SELECT fields FROM property_type WHERE id = $property_id LIMIT 1"); 
$fieldTypeId = $data[0]->fields;  

$data = $boj->getQuery("SELECT * FROM property_fields WHERE id IN ($fieldTypeId)"); 

$html = '<div class="form-group">'; 
$allIds =  array();
foreach ($data as $value) {
	$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $value->field_name)));
	$html .= '<label class="col-sm-3 control-label">'.$value->field_name.'</label>';
	$html .= '<div class="col-sm-3">
				<input type="text" name="field_type_'.$value->id.'"  class="form-control" placeholder="'.$value->field_name.'">
				<label class="error" for="price"></label>
			  </div>';
 array_push($allIds, $value->id);	  
}

$html .= '</div>';
$result = array('htmls'=>$html,'allIds'=>implode(',',$allIds));
echo json_encode($result);

exit;