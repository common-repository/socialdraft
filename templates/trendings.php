<style>
	.sdapp .tablenav.top label{
		float: left;
		padding-top: 5px;
		margin: 0 5px 0 0;
	}
	.sdapp .tablenav.top select{
		margin: 0 15px 0 0;
	}
	.sdapp .tablenav.top p.submit{
		margin: 0;
		padding: 0;
	}
	.sdapp .postbox h3.hndle{
		font-size: 18px;
	}
	.sdapp ul.sd-trending-list li{
		float: left;
	}
	.sdapp .word {
	  	height: 18pt;
		margin: 0px 10px 10px 0px;
	  	text-align: center;
	  	text-decoration: none;
	  	vertical-align: bottom;
	  	color: #1694E6;
		font-family: "Helvetica", "Arial", sans-serif;
		vertical-align:text-bottom;
		display: inline-block;
	}
	.sdapp .word:hover {
		text-decoration:none !important;
		color: #003253 !important;
	}
	[class^="size"], [class*=" size"]{
		line-height: 30px;
	}
	.sdapp .sd-trending-list li a{
		color: #015790;
	}
	.sdapp .size23 {
		font-size: 30pt;
	}
	.sdapp .size22 {
		font-size: 29pt;
	}
	.sdapp .size21 {
		font-size: 28pt;
	}
	.sdapp .size20 {
		font-size: 27pt;
	}
	.sdapp .size19 {
		font-size: 26pt;
	}
	.sdapp .size18 {
		font-size: 25pt;
	}
	.sdapp .size17 {
		font-size: 24pt;
	}
	.sdapp .size16 {
		font-size: 23pt;
	}
	.sdapp .size15 {
		font-size: 22pt;
	}
	.sdapp .size14 {
		font-size: 21pt;
	}
	.sdapp .size13 {
		font-size: 20pt;
	}
	.sdapp .size12 {
		font-size: 19pt;
	}
	.sdapp .size11 {
		font-size: 18pt;
	}
	.sdapp .size10 {
		font-size: 17pt;
	}
	.sdapp .size9 {
		font-size: 17pt;
	}
	.sdapp .size8 {
		font-size: 16pt;
		color: #0b77bd !important;
	}
	.sdapp .size7 {
		font-size: 15pt;
		color: #0e7cc2 !important;
	}
	.sdapp .size6 {
		font-size: 14pt;
		color: #239be8 !important;
	}
	.sdapp .size5 {
		font-size: 13pt;
		color: #2da2ee !important;
	}
	.sdapp .size4 {
		font-size: 12pt;
		color: #37aaf4 !important;
	}
	.sdapp .size3 {
		font-size: 11pt;
		color: #48b4f9 !important;
	}
	.sdapp .size2 {
		font-size: 10pt;
		color: #5abfff !important;
	}
	.sdapp .size1 {
		font-size: 9pt;
		color: #0090ed !important;
	}
	.sdapp .size0 {
		font-size: 8pt;
		color: #0090ed !important;
	}
	#TB_window{
		box-shadow: rgba(0,0,0,-2.8) 0 4px 30px !important;
	}
	#TB_window #TB_title{
		background: none repeat scroll 0 0 #7e8a93 !important;
	}
	#TB_ajaxContent ul, #TB_ajaxContent li{
		list-style-type: none;
	}
	.sd-trending-review {
		color: #5b5b5b;
		border-bottom: 1px solid #ddd;
		padding: 15px;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}
	.sd-trending-review.head-thick{
		font-size: 15px;
	}
	.sd-trending-review img{
		margin: 0 0 5px 0;
	}
	.sd-trending-review .trending-date{
		font-style: italic;
	}
	.sd-trending-review .trending-rating{
		padding-left: 10px;
	}
	.sd-trending-review .trending-highlight{
		padding: 10px 0 0 0;
		display: block;
	}
	.sd-trending-review .highlight-pos b, .sd-trending-review  .highlight-neg b, .sd-trending-review .highlight-neu b {
		background-color: #76d5ff;
		padding: 0 5px;
	}
</style>

<div class="wrap sdapp">
    <h2>Socialdraft - Trendings</h2>
	<form action="" id="trendings-form"  name="trendings-form" method="POST">
		<div class="tablenav top">
			<p class="search-box"></p>
			<div class="alignleft actions">
				<label for="biz">Business: </label>
				<select id="biz" name="biz">
				<?php
				$appid = get_option("setting_appid");
				$appkey = get_option("setting_appkey");
				$email = get_option("setting_email");

				$byid_list_api_url = wp_remote_get('http://72.44.39.248/api/v1/business/email='.$email.'&appid='.$appid.'&appkey='.$appkey);

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
						
						if ($_POST['biz'] == $biz_options_text) {
							$selected = "selected";
						}
						echo '<option value="'.$biz_options_text.'" name="'.$biz_options_text.'" '.$selected.'>'.$business_id["name"].'</option>';
					}
				}
				?>
				</select>			
				<label for='schedule'>Schedule: </label>
				<select id='schedule' name="schedule">
					<option value="week" <?php if ($_POST['schedule']=="week") { echo 'selected'; } ?>>Week</option>
					<option value="month" <?php if ($_POST['schedule']=="month") { echo 'selected'; } ?>>Month</option>
					<option value="quarter" <?php if ($_POST['schedule']=="quarter") { echo 'selected'; } ?>>Quarter</option>
					<option value="year" <?php if ($_POST['schedule']=="year") { echo 'selected'; } ?>>Year</option>
				</select>
				<label for='type'>Type: </label>
				<select id='type' name="type">
					<option value="all" <?php if ($_POST['type']=="all") { echo 'selected'; } ?>>All</option>
					<option value="positive" <?php if ($_POST['type']=="positive") { echo 'selected'; } ?>>Positive</option>
					<option value="neutral" <?php if ($_POST['type']=="neutral") { echo 'selected'; } ?>>Neutral</option>
					<option value="negative" <?php if ($_POST['type']=="negative") { echo 'selected'; } ?>>Negative</option>
				</select>
				<div class="alignleft">
					<?php @submit_button('Filter', 'button-secondary'); ?>
				</div>
			</div>
			<br class="clear">
		</div>
	</form>

	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
	    	<div id="normal-sortables" class="meta-box-sortables ui-sortable">
	    		<div class="postbox">
	    			<?php add_thickbox(); ?>
					<?php
					$type = $_POST["type"];
					$schedule = $_POST["schedule"];
					$biz = $_POST["biz"];
					$biz_options = explode("_", $biz);
					$byid = $biz_options[0];
					$byname = $biz_options[1];
					if (!empty($_REQUEST["submit"])){
						echo "<h3 class='hndle'>".$byname." - ". ucfirst($type)." Trends by ".ucfirst($schedule)."</h3>";
						$trendings_api_url = wp_remote_get('http://72.44.39.248/api/v1/trendingsv2/byid='.$byid.'&type='.$type.'&schedule='.$schedule.'&appid='.$appid.'&appkey='.$appkey, array( 'timeout' => 120));
					} else {
						echo "<h3 class='hndle'>$default_byname - Positive Trends by Quarter</h3>";
						$type = 'positive';
						$schedule = 'quarter';
						$trendings_api_url = wp_remote_get('http://72.44.39.248/api/v1/trendingsv2/byid='.$default_byid.'&type='.$type.'&schedule='.$schedule.'&appid='.$appid.'&appkey='.$appkey, array( 'timeout' => 120));
						#$trendings_api_url = wp_remote_get('http://72.44.39.248/api/v1/trendingsv2/byid=55&type=positive&schedule=year&appid=de4f43c9&appkey=32ecbd789230abff72d1e7302dc6d776', array( 'timeout' => 120));
					}
					echo "<div class='inside'>";
					if ($trendings_api_url["response"]["code"] == 200) {
						$trendings_body = $trendings_api_url["body"];
						$response = json_decode($trendings_body, true);

						echo "<ul class='sd-trending-list'>";
						foreach($response as $trends){
							echo "
							<li>
								<a href='#TB_inline?height=500&amp;width=550&amp;inlineId=trending-id-".str_replace(" ","-",$trends["tag"]["tag"])."' class='word thickbox size".$trends["tag"]["size"]." count".$trends["tag"]["rowcount"]."'>
								".$trends["tag"]["tag"]."
								</a>
							</li>

							<div id='trending-id-".str_replace(" ","-",$trends["tag"]["tag"])."' style='display:none;'>";
								echo "<li class='sd-trending-review head-thick'>".$trends["tag"]["sentiment_count"]." reviews mentioned <strong>\"".$trends["tag"]["tag"]."\"</strong> this $schedule.</li>";
								if($type=='all') {
									foreach($trends["reviews"][key($trends["reviews"])] as $review){
										echo "<li class='sd-trending-review'><img src='http://app.socialdraft.com/img/review-icon/".$review["review_source"].".png' /><br />
											<span class='trending-date'>".$review["review_date"]."</span>
											<span class='trending-rating'>Rating: ".$review["rating"]."/5</span> <br />
											<span class='trending-highlight'>".$review["review_highlight"]."</span></li>";
									}
								} else {
									foreach($trends["reviews"][$type] as $review){
										echo "<li class='sd-trending-review'><img src='http://app.socialdraft.com/img/review-icon/".$review["review_source"].".png' /><br />
											<span class='trending-date'>".$review["review_date"]."</span>
											<span class='trending-rating'>Rating: ".$review["rating"]."/5</span> <br />
											<span class='trending-highlight'>".$review["review_highlight"]."</span></li>";
									}								
								}
							echo "</div>";

						}
						echo "</ul>";
					} else {
						switch($trendings_api_url["response"]["code"]) {
							case "500": echo "No results found."; break;
							case "404": echo "Please input valid API Credentials."; break;
						}
					}
					echo "<br class='clear'></div>";
					?>
				</div>
			</div>
		</div>
	</div>
</div>