<?php
/*
Plugin Name: Bigcommerce User Approval
Plugin URI:https://github.com/smrutiranjan/bcuserapproval
Description: This plugin collect data from typeform by webhook and create customer at bigcommerce site if site admin approve that user. <a href="https://github.com/smrutiranjan/bcuserapproval/archive/master.zip" target="_blank">click here to you can download latest update.</a>
Author: Smrutiranjan 
Version: 1.1
*/
register_activation_hook(__FILE__,'bcuserapproval_install');
function bcuserapproval_install()
{
	return ''; 
}
add_action( 'init', 'functionbcusers' ); 
function functionbcusers() {
	 register_post_status( 'approved', array(
            'label'                     => _x( 'Approved', 'post' ),
            'label_count'               => _n_noop( 'Approved <span class="count">(%s)</span>', 'Approved <span class="count">(%s)</span>'),
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true
        ));
		 register_post_status( 'executed', array(
            'label'                     => _x( 'Executed', 'post' ),
            'label_count'               => _n_noop( 'Executed <span class="count">(%s)</span>', 'Executed <span class="count">(%s)</span>'),
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true
        ));
	if(!post_type_exists('bcuser')){
		register_post_type( 'bcuser',
			array(
				'labels' => array(
					'name' => __( 'BC User' ),
					'singular_name' => __( 'BC User' ),
					'search_items' => __("Search BC User"),
					'not_found' =>  __('No bc user found'),
					'not_found_in_trash' => __('No bc user found in Trash'),
				    'parent_item_colon' => ''
				),
				'menu_position'	=> 100,
				'public' => true,
				'publicly_queryable' => true,
				'query_var' => true,				
				'hierarchical' => false,
				'show_ui' => true,
				'show_in_menu'	=> false,
				'show_in_admin_bar' => false,
				'has_archive' => true,
				'supports' => array('title','custom-fields')
				)
		);
		
	}
}

 function my_custom_status_add_in_quick_edit() {
        echo "<script>
        jQuery(document).ready( function() {
            jQuery( 'select[name=\"_status\"]' ).append( '<option value=\"approved\">Approved</option>' ); 	
        }); 
        </script>";
    }
    add_action('admin_footer-edit.php','my_custom_status_add_in_quick_edit');
    function my_custom_status_add_in_post_page() {
        global $post;
        $label    = " Approve";
        $complete = "<option value='approved'>$label</option>";
        if ( $post->post_status == 'approved' ) {
            $label    = " Approved";
            $complete = "<option value='approved' selected='selected'>$label</option>";
        } 
        ob_start();
        ?>
        <script>
            jQuery(document).ready(function ($) {
                var label = "<?= $label ?>";
                $("select#post_status").append("<?= $complete ?>");
                if (' Approved' == label){
                    $(".misc-pub-section #post-status-display").html(label);
                }
            });
        </script>
        <?php
        echo ob_get_clean();
    }
    add_action('admin_footer-post.php', 'my_custom_status_add_in_post_page');
    add_action('admin_footer-post-new.php', 'my_custom_status_add_in_post_page');
if ( is_admin() ){ // admin actions
	add_action('admin_menu', 'bcuserapproval_setting'); 
	add_action('save_post_bcuser', 'savebcusermeta', 10, 3);
}
add_filter( 'manage_edit-bcuser_columns', 'set_custom_edit_bcuser_columns' );
function set_custom_edit_bcuser_columns($columns) {	
 
	unset( $columns['language'] );
	unset( $columns['seopressor_keyword'] );
	unset( $columns['seopressor_score'] ); 
	unset( $columns['title'] );	
	$columns['First Name'] = 'Name';
  	$columns['Email'] = 'Email';
    $columns['Company'] = 'Company';
    $columns['Phone Number'] = 'Phone';
	$columns['PERC Location ID'] = 'PERC Location ID';
	$columns['Invitation Code'] = 'Invitation Code';
	$columns['status'] = 'Status';
    return $columns;
}
add_action( 'manage_bcuser_posts_custom_column' , 'custom_bcuser_column', 10, 2 );
function custom_bcuser_column( $column, $post_id ) {
    switch ( $column ) {		
		case 'Company' :
            if(get_post_meta( $post_id , 'Company' , true ) > 0){
             echo get_post_meta( $post_id , 'Company' , true );			 
			} else {
				 echo "xxx";			 
			}
            break;
		case 'status' :
             echo '<strong>'.ucwords(get_post_status( $post_id)).'</strong>';
            break;
		case 'First Name' :
            if(get_post_meta( $post_id , 'First Name' , true ) > 0){
             echo "<a href='".get_edit_post_link($post_id)."'>".get_post_meta( $post_id , 'First Name' , true )." ".get_post_meta( $post_id , 'Last Name' , true )."</a>";			 
			} else {
				 echo "xxx";			 
			}
            break;
		case 'Email' :
            if(get_post_meta( $post_id , 'Email' , true ) > 0){
             echo get_post_meta( $post_id , 'Email' , true );			 
			} else {
				 echo "xxx";			 
			}
            break;
		case 'Phone Number' :
            if(get_post_meta( $post_id , 'Phone Number' , true ) > 0){
             echo get_post_meta( $post_id , 'Phone Number' , true );			 
			} else {
				 echo "xxx";			 
			}
            break;
		case 'PERC Location ID' :
            if(get_post_meta( $post_id , 'PERC Location ID' , true ) > 0){
             echo get_post_meta( $post_id , 'PERC Location ID' , true );			 
			} else {
				 echo "xxx";			 
			}
            break;
		case 'Invitation Code' :
            if(get_post_meta( $post_id , 'Invitation Code' , true ) > 0){
             echo get_post_meta( $post_id , 'Invitation Code' , true );			 
			} else {
				 echo "xxx";			 
			}
            break;
	}
}
function savebcusermeta($post_id, $post, $update){
	$post_status = get_post_status($post_id); 
	if ( $post_status == 'approved' ) { 
	/*call Bc code to create customer at BC backend */
		$clientid=get_option("bcuser-client");
		$token=get_option("bcuser-token");
		$hash=get_option("bcuser-hash"); 
		if(!empty($clientid) && !empty($token) && !empty($hash)){ 
			$lname=get_post_meta($post_id, 'Last Name',true);
			$email=get_post_meta($post_id, 'Email',true);
			$fname=get_post_meta($post_id, 'First Name',true);
			$company=get_post_meta($post_id, 'Company',true);
			$phone=get_post_meta($post_id, 'Phone Number',true);
			$password=get_post_meta($post_id, 'Password',true);
			
			$PERCLocationID=get_post_meta($post_id, 'PERC Location ID',true);
			$InvitationCode=get_post_meta($post_id, 'Invitation Code',true);
			
			$url="https://api.bigcommerce.com/stores/$hash/v3/customers";  
			$data_string =trim('[
			  {
				"email": "'.$email.'",
				"first_name": "'.$fname.'",
				"last_name": "'.$lname.'",
				"company": "'.$company.'",
				"phone": "'.$phone.'",
				"notes": "created by typeform hook api",
				"customer_group_id": 2,
				"addresses": [
				  {
					"first_name": "'.$fname.'",
					"last_name": "'.$lname.'",
					"address1": "Addr 1",
					"city": "San Francisco",
					"company": "'.$company.'",
					"state_or_province":  "California",
					"postal_code":"33333",
					"country_code": "US",
					"phone": "'.$phone.'",
					"address_type": "commercial"
				  } 
				],    
				"authentication": {
				  "force_password_reset": false,
				  "new_password": "'.$password.'"
				}
			  }
			]');  
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,60);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                   
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);              
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                    
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept:application/json','Content-Length: ' . strlen($data_string),'X-Auth-Client:'.(string)$clientid,'X-Auth-Token:'.(string)$token
	)); 
	
	$response = curl_exec($ch); 
	$err = curl_error($ch);
	curl_close($ch); 
	if ($err) {
		//echo "cURL Error #:" . $err; 
		} else {
			//echo $response; 
			wp_update_post( array('ID'=>$post_id,'post_status'=>'executed') );
			$customerobj=json_decode($response); 
			
			$url1="https://api.bigcommerce.com/stores/$hash/v3/customers/form-field-values";
			$data_string1='
			 [
				{
					"name": "Invitation Code",
					"customer_id": '.$customerobj->data[0]->id.',
					"value": "'.$InvitationCode.'"
				},
				{
					"name": "PERC Location ID",
					"customer_id": '.$customerobj->data[0]->id.',
					"value": "'.$PERCLocationID.'"
				} 				
			  ]
			  '; 
	$ch1 = curl_init();
	curl_setopt($ch1, CURLOPT_HEADER, 0);
	curl_setopt($ch1, CURLOPT_RETURNTRANSFER,1); 
	curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT,60);
	curl_setopt($ch1,CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch1,CURLOPT_URL, $url1);
	curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "PUT");                   
	curl_setopt($ch1, CURLOPT_POSTFIELDS, $data_string1);              
	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);                     
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept:application/json','Content-Length: ' . strlen($data_string),'X-Auth-Client:'.(string)$clientid,'X-Auth-Token:'.(string)$token
	)); 
	$result = curl_exec($ch1);
	$err1 = curl_error($ch1);
		curl_close($ch1);	
			if ($err1) {
			 // echo "cURL Error #:" . $err1;
			} else {
			  //echo $result;
			}
			
		}
	} 
	}  
	 
}
function bcuserapproval_setting(){
	add_menu_page( 'BC User', 'BC User', 'manage_options', 'bcuser_setting', 'buusersetting'); 
	add_submenu_page('bcuser_setting',  'Users','Users', 'manage_options', 'edit.php?post_type=bcuser');
} 
function buusersetting() {
	$msg='';
	if(isset($_POST['save'])){		
		
		if(isset($_POST["bcuser-client"]))
		{
			delete_option( 'bcuser-client');
			add_option( 'bcuser-client',$_POST["bcuser-client"], '', 'yes' ); 
		}
		
		if(isset($_POST["bcuser-token"]))
		{
			delete_option( 'bcuser-token');
			add_option( 'bcuser-token',$_POST["bcuser-token"], '', 'yes' ); 
		}
		if(isset($_POST['bcuser-hash']))
		{
			delete_option( 'bcuser-hash');
			add_option( 'bcuser-hash',$_POST["bcuser-hash"], '', 'yes' ); 
		}
		$msg="<div style='margin:10px 0;color:green;font-size:19px;font-weight:bold'>Setting has been saved successfully.</div>";
	}
	?>
	<div class="pea_admin_wrap">
        <div class="pea_admin_top" style="margin:30px 0;">
            <h1>Bigcommerce User Approval Api Setting</h1>
        </div>        
 		<?php if($msg!=""){ echo '<div class="msg">'.$msg.'</div>';}?>
        <div class="pea_admin_main_wrap">
            <div class="pea_admin_main_left">
            <form method="post" action="" name="form1" enctype="multipart/form-data">
            	<p><strong>X-Auth-Client</strong>&nbsp;&nbsp;&nbsp;<input type="text" name="bcuser-client" value="<?php echo get_option('bcuser-client');?>" style="width: 100%;max-width: 500px;"/></p><p><em>Example like f1y5cg05o69spngrzbdi3yuozh63auu</em></p>
				<p>&nbsp;</p>
                <p><strong>X-Auth-Token</strong>&nbsp;&nbsp;&nbsp;<input type="text" name="bcuser-token" value="<?php echo get_option('bcuser-token');?>" style="width: 100%;max-width: 500px;"/></p><p><em>Example like 2cvmp6ed8npcj2do3dboxinmr65qybb</em></p>
				<p>&nbsp;</p>
				<p><strong>Store Hash</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="bcuser-hash" value="<?php echo get_option('bcuser-hash');?>" style="width: 100%;max-width: 500px;"/></p><p><em>Example like hgpnfn9670</em></p><p>&nbsp;</p>
				<div style="float: left; width: 18%;" class="submit">
                    <input type="submit" style="float: right;" name="save" value="Save Settings" class="button-primary">
                </div>
			</form> 					
            </div>
		</div> 
 <div style="clear:both;margin-top:20px"> 
<p>TYPEFORM WebHook Url</p> 
<p><strong><?php echo plugin_dir_url( __FILE__ )."webhook.php";?></strong></p>	 
<p>Set up Crontab -e as below..<br/>
* * * * * <?php echo plugin_dir_path( __FILE__ );?>/cron.php 
</p>       
    </div>
	</div>
	<?php
}
?>