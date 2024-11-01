<style>
	.sdapp .tablenav.top{
		clear: none;
		float: left;
	}
	.sdapp .tablenav.top label{
		float: left;
		padding-top: 5px;
		margin: 0 5px 0 0;
	}
	.sdapp .tablenav.top p.submit{
		margin: 0;
		padding: 0;
	}
	.sdapp #dashboard-widgets-wrap {
		margin: 20px -8px;
	}
	.sdapp .postbox h3.hndle{
		font-size: 18px;
	}
	.sdapp .customers-contain{
		float: left;
		width: 220px;
		padding: 10px;
		border: 1px solid #ddd;
		margin: 0 10px 10px 0;
		box-sizing: border-box;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		-ms-box-sizing: border-box;
		-o-box-sizing: border-box;
	}
	.sdapp .customers-contain img{
		float: left;
		margin-right: 10px;
	}
	.sdapp .pagination {
		padding: 3px;
		margin: 10px 5px;
		text-align:center;
		font-family:Tahoma,Helvetica,sans-serif;
		font-size:.85em;
		float: right;
	}
	.sdapp #customers-form .pagination {
		margin: 10px 0;
	}
	.sdapp .pagination a {
		border: 1px solid #ddd;
		margin-right:5px;
		padding:5px 8px;
		background-position:bottom;
		text-decoration: none;
		color: #0074a2;
		background: #eee;
		background: rgba(0,0,0,.05);
	}
	.sdapp .pagination a:hover, .sdapp .pagination a:active {
		background-image:none;
		background-color:#2ea2cc;
		color: #fff;
	}
	.sdapp .pagination span.current {
		margin-right:3px;
		padding:2px 6px;
		
		font-weight: bold;
		color: #000;
	}
	.sdapp .pagination span.disabled {
		display:none;
	}
	.sdapp .pagination a.next{
		margin:0 0 0 10px;
	}
	.sdapp .pagination a.prev{
		margin:0 10px 0 0;
	}	
	.sdapp select#offset{
		display: none;
	}
	.sdapp label[for="offset"] {
		display:none;
	}
	@media screen and (max-width: 1652px){
		.sdapp .customers-contain{
			width: 19%;
			margin: 0 1% 10px 0;
		}
	}
	@media handheld, only screen and (max-width: 1290px){
		.sdapp .tablenav.top{
			margin-bottom: 20px; 
		}
		.sdapp .pagination{
			clear: both;
			float: none;
		}
		.sdapp #dashboard-widgets-wrap{
			margin: 10px -8px;
		}
	}
	@media screen and (max-width: 1343px){
		.sdapp .customers-contain{
			width: 24%;
			margin: 0 1% 10px 0;
		}
	}
	@media screen and (max-width: 1190px){
		.sdapp .customers-contain{
			width: 240;
			margin: 0 1% 10px 0;
		}
	}
	@media screen and (max-width: 1054px){
		.sdapp .customers-contain{
			width: 30%;
			margin: 0 1% 10px 0;
		}
	}
	@media screen and (max-width: 900px){
		.sdapp .customers-contain{
			width: 220px;
			margin: 0 1% 10px 0;
		}
	}
	@media screen and (max-width: 806px){
		.sdapp .customers-contain{
			width: 200px;
			margin: 0 1% 10px 0;
		}
	}
	@media screen and (max-width: 782px){
		.tablenav.top .actions, .view-switch {
			display: inline-block;
		}
	}
	@media screen and (max-width: 690px){
		.sdapp .customers-contain{
			width: 45%;
			margin: 0 3% 10px 0;
		}
	}
	@media screen and (max-width: 500px){
		.sdapp .customers-contain{
			width: 40%;
			margin: 0 2% 10px 0;
		}
	}
	@media screen and (max-width: 485px){
		.sdapp .customers-contain{
			width: 100%;
			margin: 0 1% 10px 0;
		}
	}
</style>
<script src="http://code.jquery.com/jquery-1.11.1.js"></script>
<script type="text/javascript">
$(function(){
	$("#TB_ajaxWindowTitle").val('')

    $(document).on("click", 'div.pagination > a', function(e) {

		current_biz = $('#customers-form #biz option:selected').val();
		current_limit = $('#customers-form #limit option:selected').val();
		current_offset = $('#customers-form #offset option:selected').val();
		var url = $(this).attr('href');
		//var $page = $(this).text();
		var $page = getURLParameter(url, 'p');
		replace_biz = current_biz.replace(/\s/g,"-");
		$('#customers-form #biz option[name='+replace_biz+']').attr("selected", true);
		$('#customers-form #limit option[name=limit_'+current_limit+']').attr("selected", true);
		$('#customers-form #offset option[name=offset_'+$page+']').attr("selected", true);

        $.ajax({
           url: "http://socialdraft.com/wp-admin/admin.php?page=socialdraft-customers&p="+$page,
           type: "POST",
           dataType: "html",
           data: $('#customers-form').serialize(),
            beforeSend: function(){
              $('#dashboard-widgets-wrap').html('<img src="https://bitgiving.com/themes/bootstrapfront/images/ajax-loader1.gif" />');
            },
		   error: function(jqXHR, textStatus, errorThrown) {
			$('#dashboard-widgets-wrap').html(textStatus);
		   },
           success: function(response){
			    var jqObj = jQuery(response);
				//$("div.postbox").empty().append(jqObj);
				$('body').html(jqObj);
           }
        });

        e.preventDefault();
    });

	$('div.customers-contain > img').brokenImage({replacement: 'http://app.socialdraft.com/images/sd-default-avatar.jpg'});
});

function getURLParameter(url, name) {
    return (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1];
}
</script>
<div class="wrap sdapp">

	<h2>Socialdraft - Customers</h2>
	<form action="" id="customers-form" name="customers-form" method="POST">
		<div class="tablenav top">
			<p class="search-box"></p>
			<div class="alignleft actions">
				<label for="biz">Business: </label>
				<select id="biz" name="biz">
				<?php
				$appid = get_option("setting_appid");
				$appkey = get_option("setting_appkey");
				$email = get_option("setting_email");
				$_SESSION['limit'] = (isset($_REQUEST["limit"])) ? $_REQUEST["limit"] : ((isset($_SESSION["limit"])) ? $_SESSION["limit"] : 10);
				$customer_count = $_SESSION['limit'];

				$byid_list_api_url = wp_remote_get('http://72.44.39.248/api/v1/business/email='.$email.'&appid='.$appid.'&appkey='.$appkey, array( 'timeout' => 120));

				if ($byid_list_api_url["response"]["code"] == 200) {
					$byid_list_body = $byid_list_api_url["body"];
					$response = json_decode($byid_list_body, true);

					foreach($response["business"] as $business_id){
						if ($business_id === reset($response["business"])) {
							$default_byid = $business_id["business_id"];
							$default_byname = $business_id["name"];
						}
						$biz_replace = str_replace(' ', '-', $business_id["name"]);
						$biz_options_text = $business_id["business_id"] . '_' . $biz_replace;
						$selected = '';
						
						$_SESSION['biz_text'] = (isset($_REQUEST["biz_text"])) ? $_REQUEST["biz_text"] : (((isset($_SESSION["biz_text"])) && $_SESSION["biz_text"] != '') ? $_SESSION["biz_text"] : $business_id["name"]);
						$_SESSION["biz"] = (isset($_REQUEST["biz"])) ? $_REQUEST["biz"] : ((isset($_SESSION["biz"])) ? $_SESSION["biz"] : $biz_options_text);
						$request_biz = $_SESSION["biz"];
						$biz_text_val = str_replace('_','', str_replace('-',' ',stristr($request_biz, '_')));
						if ($biz_text_val == $business_id["name"]) {
							$biz_id_selected = $business_id["business_id"];
							$selected = "selected";
							$biz_text_val = $business_id["name"];
						}
						
						echo '<option data-name="'.$biz_text_val.'" value="'.$biz_options_text.'" name="'.$biz_options_text.'" '.$selected.'>'.$business_id["name"].'</option>';
					}
				}
				?>
				</select>
				<label for="limit">Limit: </label>
				<select id='limit' name="limit">
					<option value="10" <?php if ($customer_count==10) { echo "selected"; } ?> name="limit_10">10</option>
					<option value="25" <?php if ($customer_count==25) { echo "selected"; } ?> name="limit_25">25</option>
					<option value="50" <?php if ($customer_count==50) { echo "selected"; } ?> name="limit_50">50</option>
					<option value="100" <?php if ($customer_count==100) { echo "selected"; } ?> name="limit_100">100</option>
				</select>
				<label for="offset">Page: </label>
				<select id="offset" name="offset">
					<?php
					$offset = 1;
					if(isset($_REQUEST["p"]) && $_REQUEST["p"] > 1){
						#$offset = (($_REQUEST["p"] - 1) * $customer_count) + 1;
						$offset = (($_REQUEST["p"] - 1) * $customer_count);
					}

					$customer_source = ($_REQUEST['customer_source']=='all') ?  '' : '&source='.$_REQUEST['customer_source'];
					$customers_api_url = wp_remote_get('http://72.44.39.248/api/v1/customers/byid='.$default_byid.$customer_source.'&limit='.$customer_count.'&offset='.$offset.'&appid='.$appid.'&appkey='.$appkey, array( 'timeout' => 120));
					$customers_body = $customers_api_url["body"];
					$cust_response = json_decode($customers_body, true);
					$total_customers = $cust_response["total_customer"];

					$loop_count = ceil($total_customers / $customer_count);
					$x=1;
					for($i=1; $i<=$total_customers; $i+=$customer_count) {
						$selected = ($x == $_REQUEST["p"]) ? 'selected' : '';
						echo '<option value="'.$i.'" name="offset_'.$i.'" '.$selected.'>'.$x.'</option>';

						$x++;
					}
					?>
				</select>
				<label for="customer_source">Source: </label>
				<select id="customer_source" name="customer_source">
					<option value="all" <?php if ($_REQUEST['customer_source']=="all") { echo 'selected'; } ?>>All</option>
					<?php
					if (empty($_REQUEST["submit"]) && (empty($_REQUEST["p"]) || $_REQUEST["p"] <= 1))	{
						$sources_list_api_url = wp_remote_get('http://72.44.39.248/api/v1/source/businessid='.$default_byid.'&appid='.$appid.'&appkey='.$appkey, array( 'timeout' => 120));
					} else {
						$sources_list_api_url = wp_remote_get('http://72.44.39.248/api/v1/source/businessid='.$biz_id_selected.'&appid='.$appid.'&appkey='.$appkey, array( 'timeout' => 120));
					}
					if ($sources_list_api_url["response"]["code"] == 200) {
						$sources_list_body = $sources_list_api_url["body"];
						$sources_response = json_decode($sources_list_body, true);

						foreach($sources_response['sources'] as $source){
							$selected = '';
							if ($_REQUEST['customer_source'] == $source) {
								$selected = "selected=selected";
							}

							echo '<option value="'.$source.'"'.$selected.'>'.$source.'</option>';
						}
					}	
					?>
				</select>
				<?php echo $request_biz_text;?><div class="alignleft">
					<?php @submit_button('Filter', 'button-secondary'); ?>
				</div>
			</div>
			<br class="clear">
		</div>
		<?php
			$p = new pagination;
			$p->items($total_customers);
			$p->limit($customer_count);
			$p->currentPage($_REQUEST['p']);
			$p->target("?page=socialdraft-customers");
			$p->parameterName("p");
			$p->show();
		?>
	</form>
	
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
	    	<div id="normal-sortables" class="meta-box-sortables ui-sortable">
	    		<div class="postbox">
					<?php

					$biz = $_REQUEST["biz"];
					$biz_options = explode("_", $biz);
					$byid = $biz_options[0];
					$byname = str_replace('-', ' ', $biz_options[1]);

					if (!empty($_REQUEST["submit"]) || isset($_REQUEST["p"])){
						echo "<h3 class='hndle'>$byname</h3>";
						$customers_api_url = wp_remote_get('http://72.44.39.248/api/v1/customers/byid='.$byid.$customer_source.'&limit='.$customer_count.'&offset='.$offset.'&appid='.$appid.'&appkey='.$appkey, array( 'timeout' => 120));
					} else {
						echo "<h3 class='hndle'>$default_byname</h3>";
					}

					$vip_customers_api_url = wp_remote_get('http://72.44.39.248/api/v1/customers/byid='.$byid.'&vip=1&appid='.$appid.'&appkey='.$appkey, array( 'timeout' => 120));
					$customers_body = $customers_api_url["body"];
					$vip_customers_body = $vip_customers_api_url["body"];
					$cust_response = json_decode($customers_body, true);
					$vip_cust_response = json_decode($vip_customers_body, true);
					$total_customers = $cust_response["total_customer"];

					echo "<div class='inside'>";	
					if ( 200 == $customers_api_url["response"]["code"] ) {
						$noimg_url = 'http://app.socialdraft.com/images/sd-default-avatar.jpg';
						$vip_img = 'http://fe3.assets.s3.amazonaws.com/img/icon-vip.png';
						$is_vip = '';
						
						foreach($cust_response["customers"] as $customer){
							foreach($vip_cust_response["customers"] as $vip_customer){ /*VIP checking*/
								if($vip_customer["profile"]==$customer["profile"]){
									$is_vip = "<br /><span class='customer-vip'><img src='".$vip_img."' height='30' width='30' /></span>";
								}
							}
							if( imagecreatefromjpeg($customer["image"]) === false ) {
								$customer["image"] = $noimg_url;
							}
							echo "
							<div class='customers-contain'>
								<a href='".$customer["profile"]."' target='_blank'><img src='".$customer["image"]."' height='80' width='80' /></a>
								<span class='customer-prof'><a href='".$customer["profile"]."' target='_blank'>".$customer["customer"]."</a></span><br />
								<span class='customer-loc'>".$customer["location"]."</span>
								$is_vip
							</div>
							";
						}
					} else {
						switch($customers_api_url["response"]["code"]) {
							case "500": echo "No results found."; break;
							case "404": echo "Please input valid API Credentials."; break;
						}
					}
					echo "<br class='clear'></div>";		
					?>    

				</div>
	    	</div>
		</div>
		<?php $p->show(); ?>
	</div>

	
</div>