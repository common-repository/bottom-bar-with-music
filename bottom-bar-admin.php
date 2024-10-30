<div class="wrap">

<h2>Bottom bar</h2><br />

<?php

$siteurl = get_option('siteurl');

$plug_url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '';

if (!defined('ABSPATH')) {

	return ;

}



$bb_plugin = dirname(__FILE__);

$bb_option_lang = get_settings('bb-lang');

if (empty($bb_option_lang)) $bb_option_lang = 'en';

require_once("$bb_plugin/langs/$bb_option_lang.php");

$bb_lang = $GLOBALS['bb_lang'];



if($_POST['bb_hidden'] == 'Y') {  

	$dbpwd = $_POST['bb_lang'];  

	update_option('bb-lang', $dbpwd); 

		

	$dbpwd = $_POST['bb_max_lastposts'];  

	update_option('bb-max-lastposts', $dbpwd); 

		

	$dbpwd = $_POST['bb_max_popularposts'];  

	update_option('bb-max-popularposts', $dbpwd); 

		

	$dbpwd = $_POST['bb_max_comments'];  

	update_option('bb-max-comments', $dbpwd); 

		

	print "<div class=\"updated\"><p><strong>".$bb_lang['saved']."</strong></p></div>";

}

if($_POST['bb_services_hidden'] == 'Y') {  

	$services = $_POST['bb_sharing_services'];  

	$count = count($services);

	$bb_services = "";

	for($i=0; $i<$count; $i++) {

		$bb_services = "$bb_services$services[$i], ";

	} 

	update_option('bb-services', $bb_services); 

	print "<div class=\"updated\"><p><strong>".$bb_lang['saved']."</strong></p></div>";

}

if($_POST['bb_restore'] == 'Y') {  

	update_option('bb-lang', 'en'); 

	update_option('bb-max-lastposts', '5'); 

	update_option('bb-max-popularposts', '5'); 

	update_option('bb-max-comments', '5');

	update_option('bb-services', '1, 2, 3, 4, 5, 6, 7, 8, 9, 10');

	print "<div class=\"updated\"><p><strong>".$bb_lang['saved']."</strong></p></div>";

}

?>

<ul class="navi">

	<li><a href="javascript:;" title="#tab_content_1"><?php echo $bb_lang['main']; ?></a></li>

	<li><a href="javascript:;" title="#tab_content_2"><?php echo $bb_lang['sharing']; ?></a></li>

	<li><a href="javascript:;" title="#tab_content_3"><?php echo $bb_lang['restore']; ?></a></li>

	<li><a href="javascript:;" title="#tab_content_4">INFO</a></li>

</ul>

<div class="recommendations_content">

	<div id="tab_content_1" class="single_content">

		<p></p>

		<form name="bb_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">

			<input type="hidden" name="bb_hidden" value="Y">

			<p><label><?php echo $bb_lang['language']; ?></label></p>

			<p></p>

			<table width="100%" cellpadding="5" cellspacing="5" border="0">

				<tr>

					<td><input type="radio" name="bb_lang" value="en" <?php if (get_option('bb-lang') == "en") { echo 'checked="yes"'; } ?> /> <img src="<?php echo $plug_url."/images/flag-uk.png" ?>" alt="uk" /> English</td>

					<td><input type="radio" name="bb_lang" value="es" <?php if (get_option('bb-lang') == "es") { echo 'checked="yes"'; } ?> /> <img src="<?php echo $plug_url."/images/flag-es.png" ?>" alt="es" /> Spanish</td>

					<td><input type="radio" name="bb_lang" value="de" <?php if (get_option('bb-lang') == "de") { echo 'checked="yes"'; } ?> /> <img src="<?php echo $plug_url."/images/flag-de.png" ?>" alt="de" /> Deutsch</td>

					<td><input type="radio" name="bb_lang" value="fr" <?php if (get_option('bb-lang') == "fr") { echo 'checked="yes"'; } ?> /> <img src="<?php echo $plug_url."/images/flag-fr.png" ?>" alt="fr" /> French</td>

				</tr>

				<tr>

					<td><input type="radio" name="bb_lang" value="bg" <?php if (get_option('bb-lang') == "bg") { echo 'checked="yes"'; } ?> /> <img src="<?php echo $plug_url."/images/flag-bg.png" ?>" alt="bg" /> Български</td>

					<td><input type="radio" name="bb_lang" value="ru" <?php if (get_option('bb-lang') == "ru") { echo 'checked="yes"'; } ?> /> <img src="<?php echo $plug_url."/images/flag-ru.png" ?>" alt="ru" /> Русский</td>

					<td><input type="radio" name="bb_lang" value="cz" <?php if (get_option('bb-lang') == "cz") { echo 'checked="yes"'; } ?> /> <img src="<?php echo $plug_url."/images/flag-cz.png" ?>" alt="cz" /> Czech</td>

					<td><input type="radio" name="bb_lang" value="tr" <?php if (get_option('bb-lang') == "tr") { echo 'checked="yes"'; } ?> /> <img src="<?php echo $plug_url."/images/flag-tr.png" ?>" alt="tr" /> Turkish</td>

				</tr>

				<tr>

					<td><input type="radio" name="bb_lang" value="hr" <?php if (get_option('bb-lang') == "hr") { echo 'checked="yes"'; } ?> /> <img src="<?php echo $plug_url."/images/flag-hr.png" ?>" alt="hr" /> Croation</td>

					<td><input type="radio" name="bb_lang" value="it" <?php if (get_option('bb-lang') == "it") { echo 'checked="yes"'; } ?> /> <img src="<?php echo $plug_url."/images/flag-it.png" ?>" alt="it" /> Italian</td>

					<td colspan="2"></td>

				</tr>

			</table>

			<p></p>

			<p>

				<b><?php echo $bb_lang['max_lastposts']; ?></b> 

				<select name="bb_max_lastposts"> 

				<?php

				$bb_max_lastposts = get_option('bb-max-lastposts');

				for ($i=1; $i<=10; $i++) {

					if($bb_max_lastposts == $i) { $selected = ' selected="selected"'; } else { $selected = ''; }

					echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';

				}

				?>

				</select><br />

			</p>

			<p>

				<b><?php echo $bb_lang['max_popularposts']; ?></b>

				<select name="bb_max_popularposts"> 

				<?php

				$bb_max_popularposts = get_option('bb-max-popularposts');

				for ($i=1; $i<=10; $i++) {

					if($bb_max_popularposts == $i) { $selected = ' selected="selected"'; } else { $selected = ''; }

					echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';

				}

				?>

				</select><br />

			</p>

			

			<p class="submit">

				<input type="submit" name="submit" value="<?php echo $bb_lang['update']; ?>" />

			</p>

		</form>

		

	</div>

	<div id="tab_content_2" class="single_content">

	<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">

		<input type="hidden" name="bb_services_hidden" value="Y">

		<h2><?php echo $bb_lang['services']; ?></h2>

		<p></p>

		<?php $services_array = explode(", ", get_option('bb-services')); ?>

		<table width="100%" cellpadding="5" cellspacing="10">

		<tr>

			<td><input type="checkbox" name="bb_sharing_services[]" value="11" <?php if (in_array("11", $services_array)) { echo 'checked="checked" '; } ?>/> <img src="<?php echo $plug_url."/images/icons/delicious.png" ?>" alt="delicious" /> Delicious</td>

			<td><input type="checkbox" name="bb_sharing_services[]" value="2" <?php if (in_array("2", $services_array)) { echo 'checked="checked" '; } ?>/> <img src="<?php echo $plug_url."/images/icons/digg.png" ?>" alt="digg" /> Digg</td>

			<td><input type="checkbox" name="bb_sharing_services[]" value="3" <?php if (in_array("3", $services_array)) { echo 'checked="checked" '; } ?>/> <img src="<?php echo $plug_url."/images/icons/edno23.png" ?>" alt="edno23" /> Edno23</td>

			<td><input type="checkbox" name="bb_sharing_services[]" value="4" <?php if (in_array("4", $services_array)) { echo 'checked="checked" '; } ?>/> <img src="<?php echo $plug_url."/images/icons/facebook.gif" ?>" alt="facebook" /> Facebook</td>

		</tr>

		<tr>

			<td colspan="4"></td>

		</tr>

		<tr>

			<td><input type="checkbox" name="bb_sharing_services[]" value="5" <?php if (in_array("5", $services_array)) { echo 'checked="checked"'; } ?>/> <img src="<?php echo $plug_url."/images/icons/google.png" ?>" alt="google-bookmarks" /> Google Bookmarks</td>

			<td><input type="checkbox" name="bb_sharing_services[]" value="6" <?php if (in_array("6", $services_array)) { echo 'checked="checked"'; } ?>/> <img src="<?php echo $plug_url."/images/icons/google-buzz.png" ?>" alt="google-buzz" /> Google Buzz</td>

			<td><input type="checkbox" name="bb_sharing_services[]" value="10" <?php if (in_array("10", $services_array)) { echo 'checked="checked"'; } ?>/> <img src="<?php echo $plug_url."/images/icons/reddit.png" ?>" alt="stumbleupon" /> Reddit</td>

			<td><input type="checkbox" name="bb_sharing_services[]" value="9" <?php if (in_array("9", $services_array)) { echo 'checked="checked"'; } ?>/> <img src="<?php echo $plug_url."/images/icons/stumbleupon.png" ?>" alt="bumbablog" /> StumbleUpon</td>

		</tr>

		<tr>

			<td colspan="4"></td>

		</tr>

		<tr>

			<td><input type="checkbox" name="bb_sharing_services[]" value="7" <?php if (in_array("7", $services_array)) { echo 'checked="checked"'; } ?>/> <img src="<?php echo $plug_url."/images/icons/svejo.png" ?>" alt="svejo" /> Svejo</td>

			<td><input type="checkbox" name="bb_sharing_services[]" value="8" <?php if (in_array("8", $services_array)) { echo 'checked="checked"'; } ?>/> <img src="<?php echo $plug_url."/images/icons/twitter.gif" ?>" alt="twitter" /> Twitter</td>
            
            <td><input type="checkbox" name="bb_sharing_services[]" value="1" <?php if (in_array("1", $services_array)) { echo 'checked="checked"'; } ?>/> <img src="<?php echo $plug_url."/images/icons/bumbablog.png" ?>" alt="twitter" /> BUMBABlog</td>

			<td colspan="2"></td>

		</tr>

		</table>

		<p class="submit">

			<input type="submit" name="submit" value="<?php echo $bb_lang['update']; ?>" />

		</p>		

	</form>

	</div>

	<div id="tab_content_3" class="single_content">

		<p></p>

		<p><?php echo $bb_lang['restore-txt']; ?></p>

		<form name="bb_resotre_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">

			<input type="hidden" name="bb_restore" value="Y">

			<p class="submit">

				<input class="button-primary" type="submit" value="<?php echo $bb_lang['restore']; ?>" />

			</p>

		</form>

	</div>

	<div id="tab_content_4" class="single_content">

		<h2>INFO</h2>

		<p></p>

		<ul>

			<li><b><?php echo $bb_lang['author']; ?>:</b> <a href="http://bumbablog.com/" target="_blank">BUMBABlog Música</a></li>

			<li><b><?php echo $bb_lang['url']; ?>:</b> <a href="http://bumbablog.com" target="_blank">BUMBABlog Música</a></li>

			<li><b><?php echo $bb_lang['version']; ?>:</b> 0.1.8-b</li>

			

		</ul>

	</div>

</div>

</div>