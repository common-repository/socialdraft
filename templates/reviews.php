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
	.sdapp .postbox .inside{
		margin: 0;
		padding: 0;
	}
	.sdapp .postbox table.widefat{
		display: block;
		box-sizing: border-box;
		-moz-box-sizing: border-box;
		border: none;
	}
	.sdapp .postbox table.widefat .col-author, .sdapp .postbox table.widefat .col-src{
		min-width: 100px;
		width: 10%;
	}
	.sdapp .postbox table.widefat .col-review{
		width: 100%;
	}
	.sdapp .postbox table.widefat td{
		border-bottom: 1px solid #e1e1e1;
	}
	.sdapp .postbox table.widefat .review-item{
		background-color: #fefaf7;
		width: 100%;
	}
	.sdapp .postbox table.widefat .review-item:nth-child(even){
		background-color: #f1f1f1;
	}
	.sdapp .pagination {
		padding: 3px;
		margin: 10px 5px;
		text-align:center;
		font-family:Tahoma,Helvetica,sans-serif;
		font-size:.85em;
		float: right;
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
	@media screen and (max-width: 782px){
		.tablenav.top .actions, .view-switch {
			display: inline-block;
		}
	}
</style>
<script src="http://code.jquery.com/jquery-1.11.1.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#TB_ajaxWindowTitle").val('')
	
    $(document).on("click", 'div.pagination > a', function(e) {

		current_biz = $('#reviews-form #biz option:selected').val();
		current_limit = $('#reviews-form #limit option:selected').val();
		current_offset = $('#reviews-form #offset option:selected').val();
		var url = $(this).attr('href');
		//var $page = $(this).text();
		var $page = getURLParameter(url, 'p');
		replace_biz = current_biz.replace(/\s/g,"-");
		$('#reviews-form #biz option[name='+replace_biz+']').attr("selected", true);
		$('#reviews-form #limit option[name=limit_'+current_limit+']').attr("selected", true);
		$('#reviews-form #offset option[name=offset_'+$page+']').attr("selected", true);

        $.ajax({
           url: "http://socialdraft.com/wp-admin/admin.php?page=socialdraft-reviews&p="+$page,
           type: "POST",
           dataType: "html",
           data: $('#reviews-form').serialize(),
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

	$('img#review-item-img').brokenImage({replacement: 'http://app.socialdraft.com/images/sd-default-avatar.jpg'});
});

function getURLParameter(url, name) {
    return (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1];
}
</script>
<div class="wrap sdapp">
  <h2>Socialdraft - Reviews</h2>
	<form action="#" id="reviews-form"  name="reviews-form" method="POST">
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
				$review_count = $_SESSION['limit'];				

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
					<option value="10" <?php if ($review_count==10) { echo "selected"; } ?> name="limit_10">10</option>
					<option value="25" <?php if ($review_count==25) { echo "selected"; } ?> name="limit_25">25</option>
					<option value="50" <?php if ($review_count==50) { echo "selected"; } ?> name="limit_50">50</option>
					<option value="100" <?php if ($review_count==100) { echo "selected"; } ?> name="limit_100">100</option>
				</select>
				<label for="offset">Page: </label>
				<select id="offset" name="offset">
					<?php
					$offset = 1;
					if(isset($_REQUEST["p"]) && $_REQUEST["p"] > 1){
						#$offset = (($_REQUEST["p"] - 1) * $review_count) + 1;
						$offset = (($_REQUEST["p"] - 1) * $review_count);
					}

					$review_source = ($_REQUEST['review_source']=='all') ?  '' : '&source='.$_REQUEST['review_source'];
					$reviews_api_url = wp_remote_get('http://72.44.39.248/api/v1/reviews/businessid='.$default_byid.$review_source.'&limit='.$review_count.'&offset='.$offset.'&appid='.$appid.'&appkey='.$appkey, array( 'timeout' => 120));
					$reviews_body = $reviews_api_url["body"];
					$cust_response = json_decode($reviews_body, true);
					$total_reviews = $cust_response["total_reviews"];

					$loop_count = ceil($total_reviews / $review_count);
					$x=1;
					for($i=1; $i<=$total_reviews; $i+=$review_count) {
						$selected = ($x == $_REQUEST["p"]) ? 'selected' : '';
						echo '<option value="'.$i.'" name="offset_'.$i.'" '.$selected.'>'.$x.'</option>';

						$x++;
					}
					?>
				</select>
				<label for="review_source">Source: </label>
				<select id="review_source" name="review_source">
					<option value="all" <?php if ($_REQUEST['review_source']=="all") { echo 'selected'; } ?>>All</option>
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
							if ($_REQUEST['review_source'] == $source) {
								$selected = "selected=selected";
							}

							echo '<option value="'.$source.'"'.$selected.'>'.$source.'</option>';
						}
					}	
					?>
				</select>
				<div class="alignleft">
					<?php @submit_button('Filter', 'button-secondary'); ?>
				</div>
			</div>
			<br class="clear">
		</div>
		<?php
			$p = new pagination;
			$p->items($total_reviews);
			$p->limit($review_count);
			$p->currentPage($_REQUEST['p']);
			$p->target("?page=socialdraft-reviews");
			$p->parameterName("p");
			$p->show();	
		?>
	</form>
	
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
			 <div>
		    	<div id="normal-sortables" class="meta-box-sortables ui-sortable">
		    		<div class="postbox">
						<?php
						$biz = $_REQUEST["biz"];
						$review_source = ($_REQUEST['review_source']=='all') ?  '' : '&source='.$_REQUEST['review_source'];
						$biz_options = explode("_", $biz);
						$byid = $biz_options[0];
						$byname = str_replace('-', ' ', $biz_options[1]);

						if (!empty($_REQUEST["submit"]) || isset($_REQUEST["p"])){
							echo "<h3 class='hndle'>$byname</h3>";
							$reviews_api_url = wp_remote_get('http://72.44.39.248/api/v1/reviews/businessid='.$byid.$review_source.'&limit='.$review_count.'&offset='.$offset.'&appid='.$appid.'&appkey='.$appkey, array( 'timeout' => 120));
						} else {
							echo "<h3 class='hndle'>$default_byname</h3>";
						}
						
						echo "
							<div class='inside'>
								<table class='widefat'>
									<thead>
										<tr>
											<th class='col-author'>Author</th>
											<th class='col-review'>Review</th>
											<th class='col-src'>Source</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th class='col-author'>Author</th>
											<th class='col-review'>Review</th>
											<th class='col-src'>Source</th>
										</tr>
									</tfoot>
									<tbody>		
							";
							
						if ( 200 == $reviews_api_url["response"]["code"] ) {
							$noimg_url = 'http://app.socialdraft.com/images/sd-default-avatar.jpg';
							$reviews_body = $reviews_api_url["body"];
							$review_response = json_decode($reviews_body, true);
							$total_reviews = $review_response["total_reviews"];
							
							foreach($review_response["reviews"] as $review){
								if( imagecreatefromjpeg($review["image"]) === false ) {
									$review["image"] = $noimg_url;
								}

								echo "
									<tr class='review-item'>
										<td class='col-author'>
											<a href='".$review["profile"]."' target='_blank'><img src='".$review["image"]."' height='80' width='80' id='review-item-img' /><br />".$review["reviewer"]."</a>
										</td>
										<td class='col-review'>
											Rating: ".$review["rating"]."<br />
											Published: ".date("m.d.Y", strtotime($review["published"]))."<br /><br />
											".limit_text($review["review"], 30, $review["source_url"])."
										</td>
										<td class='col-src'>
											<a href='".$review["source_url"]."' target='_blank'>".$review["source"]."</a>
										</td>
									</tr>
									";
							}
						} else {
							switch($reviews_api_url["response"]["code"]) {
								case "500": echo "<td colspan='3'>No results found.</td>"; break;
								case "404": echo "<td colspan='3'>Please input valid API Credentials.</td>"; break;
							}
						}	
						echo "
									</tbody>
								</table>
								<br class='clear'>
							</div>";
						?>	
					</div>
				</div>
			</div>
		</div>
		<?php $p->show(); ?>
	</div>
</div>