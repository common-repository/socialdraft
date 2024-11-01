<?php
require_once('pagination.class.php');
/**
 * trims text to a maximum length, splitting at last word break, and (optionally) appending ellipses and stripping HTML tags
 * @param string $input text to trim
 * @param int $length maximum number of characters allowed
 * @param bool $ellipses if ellipses (...) are to be added
 * @param bool $strip_html if html tags are to be stripped
 * @return string 
 */
function trim_better($input, $length, $ellipses = true, $strip_html = true) {
    //strip tags, if desired
    if ($strip_html) {
        $input = strip_tags($input);
    }
 
    //strip leading and trailing whitespace
    $input = trim($input);
 
    //no need to trim, already shorter than trim length
    if (strlen($input) <= $length) {
        return $input;
    }
 
    //leave space for the ellipses (...)
    if ($ellipses) {
        $length -= 3;
    }
 
    //this would be dumb, but I've seen dumber
    if ($length <= 0) {
        return '';
    }
 
    //find last space within length
    //(add 1 to length to allow space after last character - it may be your lucky day)
    $last_space = strrpos(substr($input, 0, $length + 1), ' ');
    if ($last_space === false) {
        //lame, no spaces - fallback to pure substring
        $trimmed_text = substr($input, 0, $length);
    }
    else {
        //found last space, trim to it
        $trimmed_text = substr($input, 0, $last_space);
    }
 
    //add ellipses (...)
    if ($ellipses) {
        $trimmed_text .= '...';
    }
 
    return $trimmed_text;
}

function limit_text($text, $limit, $url='') {
	if (str_word_count($text, 0) > $limit) {
	  $words = str_word_count($text, 2);
	  $pos = array_keys($words);
	  if($url!=""){
		$text = substr($text, 0, $pos[$limit]) . '<a href="'.$url.'" target="_blank">...</a>';
	  } else {
		$text = substr($text, 0, $pos[$limit]) . '...';
	  }
	}
	return $text;
}

/** Pagination **/
function findStart($limit) { 
    if ((!isset($_GET['page'])) || ($_GET['page'] == "1")) { 
        $start = 0; 
        $_GET['page'] = 1; 
    } else { 
        $start = ($_GET['page']-1) * $limit; 
    } 
    return $start; 
}
  
  /*
   * int findPages (int count, int limit) 
   * Returns the number of pages needed based on a count and a limit 
   */
function findPages($count, $limit) { 
     $pages = (($count % $limit) == 0) ? $count / $limit : floor($count / $limit) + 1; 
 
     return $pages; 
} 
 
/* 
* string pageList (int curpage, int pages) 
* Returns a list of pages in the format of "« < [pages] > »" 
**/
function pageList($curpage, $pages) { 
    $page_list  = ""; 
 
    /* Print the first and previous page links if necessary */
    if (($curpage != 1) && ($curpage)) { 
       $page_list .= "  <a href=\" ".$_SERVER['PHP_SELF']."?page=1\" title=\"First Page\">«</a> "; 
    } 
 
    if (($curpage-1) > 0) { 
       $page_list .= "<a href=\" ".$_SERVER['PHP_SELF']."?page=".($curpage-1)."\" title=\"Previous Page\"><</a> "; 
    } 
 
    /* Print the numeric page list; make the current page unlinked and bold */
    for ($i=1; $i<=$pages; $i++) { 
        if ($i == $curpage) { 
            $page_list .= "<b>".$i."</b>"; 
        } else { 
            $page_list .= "<a href=\" ".$_SERVER['PHP_SELF']."?page=".$i."\" title=\"Page ".$i."\">".$i."</a>"; 
        } 
        $page_list .= " "; 
      } 
 
     /* Print the Next and Last page links if necessary */
     if (($curpage+1) <= $pages) { 
        $page_list .= "<a href=\"".$_SERVER['PHP_SELF']."?page=".($curpage+1)."\" title=\"Next Page\">></a> "; 
     } 
 
     if (($curpage != $pages) && ($pages != 0)) { 
        $page_list .= "<a href=\"".$_SERVER['PHP_SELF']."?page=".$pages."\" title=\"Last Page\">»</a> "; 
     } 
     $page_list .= "</td>\n"; 
 
     return $page_list; 
}
  
/*
* string nextPrev (int curpage, int pages) 
* Returns "Previous | Next" string for individual pagination (it's a word!) 
*/
function nextPrev($curpage, $pages) { 
	$next_prev  = ""; 
 
    if (($curpage-1) <= 0) { 
        $next_prev .= "Previous"; 
    } else { 
        $next_prev .= "<a href=\"".$_SERVER['PHP_SELF']."?page=".($curpage-1)."\">Previous</a>"; 
    } 
 
        $next_prev .= " | "; 
 
    if (($curpage+1) > $pages) { 
        $next_prev .= "Next"; 
    } else { 
        $next_prev .= "<a href=\"".$_SERVER['PHP_SELF']."?page=".($curpage+1)."\">Next</a>"; 
    } 
    
	return $next_prev; 
}

function setSessionVars($array) {
	#$array = $_POST;
    foreach($array as $fieldname => $fieldvalue) {
        $_SESSION['form'][$fieldname] = $fieldvalue;
    }   
}

function register_session(){
    if( !session_id() )
        session_start();
}

function plugin_send_email() {
	echo "<pre>",var_dump($_POST),"</pre>";
	if (!empty($_POST["api-setting-send-email"])){
		die("here1");
		#$attachments = array( WP_CONTENT_DIR . '/uploads/file_to_attach.zip' );
		$headers = 'From: Socialdraft <admin@socialdraft.com>' . "\r\n";
		$appid = get_option("setting_appid");
		$appkey = get_option("setting_appkey");
		$email = get_option("setting_email");
		wp_mail('macelestial@gmail.com', 'Test Subject', 'MSG: Your Application ID = '.$appid.' and Application Key = '.$appkey, $headers);
	} else {
		die("here2");
	}

} // end my_theme_send_email

?>