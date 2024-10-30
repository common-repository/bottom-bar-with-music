<?php

/*

/*
Plugin Name: Bottom Bar with Music
Plugin URI: http://bumbablog.com/
Description: Bottom Bar with Music es un plugin de WordPress que cuenta con varias funcionalidades, entre las que destaca el menu de música con 22 géneros y miles de canciones. <a href="http://bumbablog.com/">Más información</a>.
Version: 0.1.8
Author: BUMBABlog
Author URI: http://bumbablog.com/
*/




if (!defined('ABSPATH')) {

	return ;

}



// LANG

register_activation_hook(__FILE__,'bb_lang_install'); 

register_deactivation_hook( __FILE__, 'bb_lang_remove' );

function bb_lang_install() {

	add_option("bb-lang", 'en', '', 'yes');

}

function bb_lang_remove() {

	delete_option('bb-lang');

}





// Recent posts

register_activation_hook(__FILE__,'bb_lastposts_install'); 

register_deactivation_hook( __FILE__, 'bb_lastposts_remove' );

function bb_lastposts_install() {

	add_option("bb-max-lastposts", '5', '', 'yes');

}

function bb_lastposts_remove() {

	delete_option('bb-max-lastposts');

}



// Popular posts

register_activation_hook(__FILE__,'bb_popularposts_install'); 

register_deactivation_hook( __FILE__, 'bb_popularposts_remove' );

function bb_popularposts_install() {

	add_option("bb-max-popularposts", '5', '', 'yes');

}

function bb_popularposts_remove() {

	delete_option('bb-max-popularposts');

}



// Recent comments

register_activation_hook(__FILE__,'bb_comments_install'); 

register_deactivation_hook( __FILE__, 'bb_comments_remove' );

function bb_comments_install() {

	add_option("bb-max-comments", '5', '', 'yes');

}

function bb_comments_remove() {

	delete_option('bb-max-comments');

}



// Services

register_activation_hook(__FILE__,'bb_services_install'); 

register_deactivation_hook( __FILE__, 'bb_services_remove' );

function bb_services_install() {

	add_option("bb-services", '1, 2, 3, 4, 5, 6, 7, 8, 9', '', 'yes');

}

function bb_services_remove() {

	delete_option('bb-services');

}





$bb_plugin = dirname(__FILE__);

$bb_option_lang = get_settings('bb-lang');

if (empty($bb_option_lang)) $bb_option_lang = 'en';

require_once("$bb_plugin/langs/$bb_option_lang.php");

$bb_lang = $GLOBALS['bb_lang'];



function bottom_bar_header() {

	$bb_url = get_settings('home') . '/wp-content/plugins/bottom-bar';
}

function bottom_bar_enqueue() {
   wp_enqueue_script('bottom-bar-js', plugins_url('controllers/bottom-bar.js',__FILE__) );
   wp_enqueue_style('bottom-bar-css', plugins_url('css/bottom-bar.css',__FILE__) );
}
add_action('wp_enqueue_scripts', 'bottom_bar_enqueue');


function bb_cut($str, $len = "155") {

	if(function_exists('mb_strlen')) {

		return mb_strlen($str,'UTF-8')<$len ? $str : (mb_substr($str,0,$len-1,'UTF-8').'...');

	}

	if( function_exists('iconv_strlen') ) {

		return iconv_strlen($str,'UTF-8')<$len ? $str : (iconv_substr($str,0,$len-1,'UTF-8').'...');

	}

	return strlen($str)<2*$len ? $str : (substr($str,0,2*$len-2).'...');

}



function bb_latest_posts($num) {

	if ($num < 1) $num = 1;

	$bb_lang = $GLOBALS['bb_lang'];

	$latest = "";

	$recent = new WP_Query("showposts=$num"); 

	while($recent->have_posts()) : $recent->the_post();

	$title = get_the_title();

	$latest .= '<li><a href="' . get_permalink() . '" title="'. $bb_lang['post'] .': '. $title .'" >'. bb_cut($title) .'</a></li>';

	endwhile;

	return $latest;

}



function bb_popular_posts($num) {

	global $wpdb;

	if ($num < 1) $num = 1;

	$bb_lang = $GLOBALS['bb_lang'];

	$posts = $wpdb->get_results("SELECT comment_count, ID, post_title FROM $wpdb->posts WHERE comment_count > 0 ORDER BY comment_count DESC LIMIT 0 , $num");

	if (empty($posts)) {

		$popular = '<li><a href="javascript:;">'. $bb_lang['nopopularposts'] .'</a></li>';

	}

	else {

		foreach ($posts as $post) {

			setup_postdata($post);

			$id = $post->ID;

			$title = $post->post_title;

			$count = $post->comment_count;

			if ($count != 0) {

				if ($count == 1) $comments = $bb_lang['comment'];

				else $comments = $bb_lang['comments'];

				$popular .= '<li><a href="' . get_permalink($id) . '" title="'. $bb_lang['post'] .': ' . $title . ' ('. $count .' '. $comments .')">' .  bb_cut($title) . '</a></li>';

			}

		}

	}

	return $popular;

}



function bb_recent_comments($num) {

	global $wpdb;

	if ($num < 1) $num = 1;

	$bb_lang = $GLOBALS['bb_lang'];

	$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type,comment_author_url, SUBSTRING(comment_content,1,$num) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT $num";

	$comments = $wpdb->get_results($sql);

	if(empty($comments)) {

		$output = '<li><a href="javascript:;">'. $bb_lang['norecentcomments'] .'</a></li>';

	} 

	else {

		$output = $pre_HTML;

		foreach ($comments as $comment) {

			$output .= "\n<li><a href=\"" . get_permalink($comment->ID) .

			"#comment-" . $comment->comment_ID . "\" title=\" ". $comment->comment_author ." ". $bb_lang['in'] ." " .

			$comment->post_title . "\">". bb_cut(strip_tags($comment->comment_author), 15)

			." ". $bb_lang['in'] ." " .  bb_cut(strip_tags($comment->post_title), 30) ."</a></li>";

		}

		$output .= $post_HTML;

	}

	return $output;

}



function bb_random_post() {

	$bb_lang = $GLOBALS['bb_lang'];

	global $wpdb, $posts;

	$random_post = $wpdb->get_var("SELECT guid FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' ORDER BY rand() LIMIT 1");

	$rand_post =  "<a href='javascript:;' onClick=\"location.href='". $random_post ."'\" class=\"random-post\">". $bb_lang['random'] ."</a>";

	return $rand_post;

}



function bb_bitly($url) {

	$short = "";

	if (function_exists('file_get_contents')) {

		$short = @file_get_contents("http://bit.ly/api?url=".$url."");

	}

	if (empty($short)) {

		return $url;

	}

	else {

		return $short;

	}

}



function bb_url() {

	$pageURL = 'http';

	if ($_SERVER["HTTPS"] == "on") {

		$pageURL .= "s";

	}

	$pageURL .= "://";

	if ($_SERVER["SERVER_PORT"] != "80") {

		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];

	} 

	else {

		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

	}

	return $pageURL;

}



function bb_title($urlpage) {

	$dom = new DOMDocument();

	if($dom->loadHTMLFile($urlpage)) {

		$list = $dom->getElementsByTagName("title");

		if ($list->length > 0) {

			return $list->item(0)->textContent;

		}

	}

}



function bb_admin_options($bb_case) {

	global $wpdb;

	if ($bb_case == 'spam') {

		$spam_comments_numer = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved = 'spam'");

		if($spam_comments_numer > 0) {

			return '<font color="#A50F00">'.$spam_comments_numer.'</font>';

		}

		else {

			return $spam_comments_numer;

		}

	}

	else if ($bb_case == 'pending') {

		$pending_comments_numer = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved = '0'");

		if($pending_comments_numer > 0) {

			return '<font color="#E66F00">'.$pending_comments_numer.'</font>';

		}

		else {

			return $pending_comments_numer;

		}

	}

	else if ($bb_case == 'totalcomments') {

		$total_comments_numer = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved = '1' AND comment_type = ''");

		return $total_comments_numer;

	}

	else if ($bb_case == 'totalposts') {

		$total_posts_numer = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post'");

		return $total_posts_numer;

	}

	else if ($bb_case == 'drafts') {

		$drafts_posts_numer = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->posts WHERE post_status = 'draft' AND post_type = 'post'");

		return $drafts_posts_numer;

	}

	else {

		return '3';

	}

}



function bottom_bar() {

	global $post;

	global $user_level;

	$bb_lang = $GLOBALS['bb_lang'];

	require_once(dirname(__FILE__)."/inc/mobile.php");

	$detect = new Mobile_Detect();

	

	if (is_single()) {

		$title = str_replace(' ','+',get_the_title());

	}

	else {

		$title = str_replace(' ','+',get_bloginfo('name'));

	}

	$url = get_permalink();

	

	$services_array = explode(", ", get_settings('bb-services')); 



	$bar = "<div id=\"bottom-bar\">

	<ul id=\"mainpanel\">    	

		<li><a href=\"javascript:;\" onClick=\"location.href='". get_settings('home') ."'\" class=\"home\">". $bb_lang['home'] ."</a></li>

		<li id='latest-posts'><a href='javascript:;' class=\"recent-posts\">". $bb_lang['latest'] ."</a>

		<div class=\"subpanel largesubpanel\">

			<h3><span> &ndash; </span> ". $bb_lang['latest'] ."</h3>

			<ul>

				". bb_latest_posts(get_settings('bb-max-lastposts')) ."

			</ul>

		</div>

		</li>



		<li id='popular-posts'><a href=\"javascript:;\" class=\"top-posts\">". $bb_lang['popular'] ."</a>

		<div class=\"subpanel largesubpanel\">

			<h3><span> &ndash; </span> ". $bb_lang['popular'] ."</h3>

			<ul>

				". bb_popular_posts(get_settings('bb-max-popularposts')) ."

			</ul>

		</div>

		</li>

		<li>". bb_random_post() ."</li>
		
		<li id='latest-comments'><a href=\"javascript:;\" class=\"recent-comments\">". $bb_lang['commentslink'] ."</a>

		<div class=\"subpanel largesubpanel\">

			<h3><span> &ndash; </span> ". $bb_lang['commentsdesc'] ."</h3>

			<ul>

				<li><iframe src=\"http://bumbablog.com/radio/rock-clasico\" width=\"100%\" height=\"215px\"></iframe></li>

			</ul>

		</div>

		</li>


		<li id='bbtop'><a title='Top' id='bb_toTop' style='display: block;'>&nbsp;</a></li>

		<li id='share'><a href=\"javascript:;\" class=\"share\">". $bb_lang['share'] ."</a>

		<div class=\"subpanel\">

			<h3><span> &ndash; </span> ". $bb_lang['share'] ."</h3>

			<ul>";

	
	if (in_array("1", $services_array)) { 

		$bar .= "<li><a href='http://bumbablog.com/register' class='bumbablog hover'>". $bb_lang['shareto'] ." BUMBABlog</a></li>";

	}
	
	
	if (in_array("11", $services_array)) { 

		$bar .= "<li><a href='http://delicious.com/save?url=" . bb_url() . "&amp;title=" . $title . "' class='delicious hover'>". $bb_lang['shareto'] ." Delicious</a></li>";

	}

	if (in_array("2", $services_array)) { 

		$bar .= "<li><a href='http://digg.com/submit?phase=2&amp;url=" . bb_url() . "&amp;title=" . $title . "' class='digg hover'>". $bb_lang['shareto'] ." Digg</a></li>";

	}

	if (in_array("3", $services_array)) { 

		$bar .= "<li><a href='http://edno23.com/pf:open/?loadlink=" . bb_url() . "&amp;loadtext=" . $title . "' class='edno23 hover'>". $bb_lang['shareto'] ." Edno23</a></li>";

	}

	if (in_array("4", $services_array)) { 

		$bar .= "<li><a href='http://facebook.com/sharer.php?u=" . bb_url() . "&amp;t=" . $title . "' class='facebook hover'>". $bb_lang['shareto'] ." Facebook</a></li>";

	}

	if (in_array("5", $services_array)) { 

		$bar .= "<li><a href='http://google.com/bookmarks/mark?op=add&amp;bkmk=" . bb_url() . "&amp;title=" . $title . "' class='google-bookmarks hover'>". $bb_lang['shareto'] ." Google Bookmarks</a></li>";

	}

	if (in_array("6", $services_array)) { 

		$bar .= "<li><a href='http://www.google.com/buzz/post?url=" . bb_url() . "&amp;title=" . $title . "' class='google-buzz hover'>". $bb_lang['shareto'] ." Google Buzz</a></li>";

	}

	if (in_array("10", $services_array)) { 

		$bar .= "<li><a href='http://www.reddit.com/submit?url=" . bb_url() . "' class='reddit hover'>". $bb_lang['shareto'] ." Reddit</a></li>";

	}

	if (in_array("9", $services_array)) { 

		$bar .= "<li><a href='http://www.stumbleupon.com/submit?url=" . bb_url() . "&amp;title=" . $title . "' class='stumbleupon hover'>". $bb_lang['shareto'] ." StumbleUpon</a></li>";

	}

	if (in_array("7", $services_array)) { 

		$bar .= "<li><a href='http://svejo.net/story/submit_by_url?url=" . bb_url() . "&amp;title=" . $title . "' class='svejo hover'>". $bb_lang['shareto'] ." Svejo</a></li>";

	}

	if (in_array("8", $services_array)) { 

		$bar .= "<li><a href='http://twitter.com/home?status=" . $title . " - " . bb_bitly(bb_url()) . "' class='twitter hover'>". $bb_lang['shareto'] ." Twitter</a></li>";

	}

	$bar .= "</ul></div></li>";

	if ($user_level == 10) {

		$bar .= "<li id='admin'><a href=\"javascript:;\" class=\"admin\">". $bb_lang['admin'] ."</a>

		<div class=\"subpanel\">

			<h3><span> &ndash; </span> ". $bb_lang['admin'] ."</h3>

			<ul>

				<li><a href='". get_settings('home')."/wp-admin'>". $bb_lang['adminpanel'] ."</a></li>

				<li><span>". $bb_lang['admin_comments'] ."</span></li>

				<li><a href='". get_settings('home')."/wp-admin/edit-comments.php?comment_status=spam'>". $bb_lang['admin_spam'] ." (". bb_admin_options('spam').")</a></li>

				<li><a href='". get_settings('home')."/wp-admin/edit-comments.php?comment_status=moderated'>". $bb_lang['admin_pending'] ." (". bb_admin_options('pending').")</a></li>

				<li><a href='". get_settings('home')."/wp-admin/edit-comments.php?comment_status=moderated'>". $bb_lang['admin_total'] ." (". bb_admin_options('totalcomments').")</a></li>

				<li><span>". $bb_lang['admin_posts'] ."</span></li>

				<li><a href='". get_settings('home')."/wp-admin/post-new.php'>". $bb_lang['admin_newpost'] ."</a></li>

				<li><a href='". get_settings('home')."/wp-admin/edit.php?post_status=publish'>". $bb_lang['admin_total'] ." (". bb_admin_options('totalposts').")</a></li>

				<li><a href='". get_settings('home')."/wp-admin/edit.php?post_status=draft'>". $bb_lang['admin_drafts'] ." (". bb_admin_options('drafts').")</a></li>

			</ul>

		</div>

		</li>

		";

	}

	$bar .= "</ul></div>";

	if ($detect->isMobile()) {

		$bar = "";

	}

	echo $bar;

}



add_filter('wp_footer', 'bottom_bar');



function bb_include_admin() {  

	include('bottom-bar-admin.php');  

}  

function bb_admin() {

	add_options_page("Bottom bar", "Bottom bar", 1, "bottom-bar", "bb_include_admin");

}

add_action('admin_menu', 'bb_admin');



function bb_admin_register_head() {

	$siteurl = get_option('siteurl');

	$url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '';

	echo "<!-- WordPress Bottom Bar -->\n";

	echo "<link rel='stylesheet' type='text/css' href='$url/css/bb-admin.css' />\n";

	echo "<script type='text/javascript' src='$url/controllers/bb-tabs.js'></script>\n";

	echo "<!-- WordPress Bottom Bar -->\n";

}

add_action('admin_head', 'bb_admin_register_head');



?>