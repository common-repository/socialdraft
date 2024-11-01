<?php
define('WP_USE_THEMES', false);
require('../wp-load.php');
?>

<style>
	.sdapp .postbox h3.hndle{
		font-size: 18px;
	}
	.sdapp .postbox .form-table th{
		width: 130px;
	}
	.sdapp .postbox .form-table input[type=text]{
		width: 100%;
	}
</style>

<div class="wrap sdapp">
    <h2>Socialdraft</h2>
	
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
			 <div id-"postbox-container1" class="postbox-container">
		    	<div id="normal-sortables" class="meta-box-sortables ui-sortable">
		    		<div class="postbox">
				    	<h3 class="hndle">Social API Settings</h3>
				    	<div class="inside">
				    		<form name="api-setting-send-email" method="post" action="options.php"> 
						        <?php @settings_fields('socialdraft-group'); ?>
						        <?php @do_settings_fields('socialdraft-group'); ?>

						        <?php do_settings_sections('socialdraft'); ?>

						        <?php @submit_button('Save Changes', 'primary', 'settings-submit'); ?>
						    </form>
							
							<?php
								#$attachments = array( WP_CONTENT_DIR . '/uploads/file_to_attach.zip' );
								$headers = 'From: Socialdraft <admin@socialdraft.com>' . "\r\n";
								$appid = get_option("setting_appid");
								$appkey = get_option("setting_appkey");
								$email = get_option("setting_email");
								$subject = 'Socialdraft API Settings';
								$body = <<<EOD
Here's your API Settings Details:

Application ID: $appid
Application Key: $appkey
EOD;

							if ($_REQUEST["settings-updated"] == 'true'){
								if(wp_mail($email, $subject, $body, $headers)){
									echo 'API credentials was sent. Please check your email inbox.';
								}
							}
							?>						
				    	</div>
					</div>
		    	</div>
			</div>
		</div>
	</div>


</div>

