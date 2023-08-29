<?php
$data = json_decode(file_get_contents('php://input'));
$answers = $data->form_response->answers;
$fields=$data->form_response->definition->fields;
$ctr=-1;
foreach($fields as $f){
	$ctr++;
	if($answers[$ctr]->type == "email"){
		$obj["$f->title"]=$answers[$ctr]->email;
	}else{
		if($answers[$ctr]->type == "phone_number"){
			$obj["$f->title"]=$answers[$ctr]->phone_number;
		}
		else
		{ 
			$obj["$f->title"]=$answers[$ctr]->text;
		}
	}
}
// Load the WordPress library.
require $_SERVER['DOCUMENT_ROOT'].'/wp-load.php'; 
if(count($obj)>1)
{
	$title=$obj["First Name"]." ".$obj["Last Name"]." ".strtotime("now");
	$my_post = array(
	  'post_title'    => $title,
	  'post_status'   => 'publish',
	  'post_type'=>'bcuser',
	  'post_author'   => 1
	);

	global $post_id;
	$post_id = wp_insert_post( $my_post );
	add_post_meta($post_id, 'First Name',$obj["First Name"]);
	add_post_meta($post_id, 'Last Name',$obj["Last Name"]);
	add_post_meta($post_id, 'Email',$obj["Email Address"]);
	add_post_meta($post_id, 'Company',$obj["Company"]);
	add_post_meta($post_id, 'Phone Number',$obj["Phone Number"]);
	add_post_meta($post_id, 'Password','Vision123456'); 
	add_post_meta($post_id, 'PERC Location ID',$obj["PERC Location ID"]);
	add_post_meta($post_id, 'Invitation Code',$obj["Invitation Code"]);
}
?>