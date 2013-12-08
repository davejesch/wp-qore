<?php
	class Cache_Assistance{
		private $menuTitle = "Cache Assistance";
		private $pageTitle = "Cache Assistance Settings";
		private $slug = "cache_assistance";
		private $adminPageUrl = "wp-qore/admin/index.php";
		private $WPconDIR = "";
		private $systemMessage = "";
		private $options = array();
		private $cronJobSettings;
		private $startTime;
		private $blockCache = false;

		public function __construct(){
			$this->setWP_ConDIR();
			$this->setOptions();
			$this->detectNewPost();
			$this->checkCronTime();
			if(is_admin()){
				$this->optionsPageRequest();
				$this->setCronJobSettings();
				$this->addButtonOnEditor();
			}
		}

		public function addButtonOnEditor(){
			add_action('admin_print_footer_scripts', array($this, 'addButtonOnQuicktagsEditor'));
			add_action('init', array($this, 'wpqore_buttonhooks'));
		}

		public function checkShortCode($content){
			preg_match("/\[NoCache\]/", $content, $NoCache);
			if(count($NoCache) > 0){
				if(is_single() || is_page()){
					$this->blockCache = true;
				}
				$content = str_replace("[NoCache]", "", $content);
			}
			return $content;
		}

		public function wpqore_buttonhooks() {
		   // Only add hooks when the current user has permissions AND is in Rich Text editor mode
		   if ( ( current_user_can('edit_posts') || current_user_can('edit_pages') ) && get_user_option('rich_editing') ) {
		     add_filter("mce_external_plugins", array($this, "wpqore_register_tinymce_javascript"));
		     add_filter('mce_buttons', array($this, 'wpqore_register_buttons'));
		   }
		}
		// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
		public function wpqore_register_tinymce_javascript($plugin_array) {
		   $plugin_array['wpqca'] = plugins_url('../js/button.js?v='.time(),__file__);
		   return $plugin_array;
		}

		public function wpqore_register_buttons($buttons) {
		   array_push($buttons, 'wpqca');
		   return $buttons;
		}

		public function addButtonOnQuicktagsEditor(){
			if (wp_script_is('quicktags')){ ?>
				<script type="text/javascript">
				    QTags.addButton('wpqca_not', 'NoCache', '[NoCache]', '', '', 'Block caching for this page');
			    </script>
		    <?php }
		}

		public function deactivate(){
		if(is_file(ABSPATH.".htaccess") && is_writable(ABSPATH.".htaccess")){
		    $htaccess = file_get_contents(ABSPATH.".htaccess");
		    $htaccess = preg_replace("/#\s?BEGIN\s?Cache_Assistance.*?#\s?END\s?Cache_Assistance/s", "", $htaccess);
		    $htaccess = preg_replace("/#\s?BEGIN\s?GzipCache_Assistance.*?#\s?END\s?GzipCache_Assistance/s", "", $htaccess);
		    file_put_contents(ABSPATH.".htaccess", $htaccess);
			}

			wp_clear_scheduled_hook("cache_assistance");
			delete_option("Cache_Assistance");
			$wpqca = new Cache_Assistance();
			$wpqca->deleteCache();
		}

		public function optionsPageRequest(){
			if(!empty($_POST)){
				if(isset($_POST["WPQore_CacheAssistancePage"])){
					if($_POST["WPQore_CacheAssistancePage"] == "options"){
						$this->saveOption();
					}else if($_POST["WPQore_CacheAssistancePage"] == "deleteCache"){
						$this->deleteCache();
					}else if($_POST["WPQore_CacheAssistancePage"] == "cacheTimeout"){
						$this->addCacheTimeout();	
					}
				}
			}
		}

		public function setWP_ConDIR(){
			$this->WPconDIR = ABSPATH."wp-content";
		}

		public function add_OptionsPanel(){
			add_action('admin_menu', array($this, 'register_CacheAssistance_menu'));
		}

		public function register_CacheAssistance_menu(){
			if(function_exists('add_menu_page')){ 
				add_submenu_page('wp-qore/functions.php', $this->pageTitle, $this->menuTitle, 'manage_options', "Cache_AssistanceOptions", array($this, 'optionsPage'));
			}
		}

		public function optionsPage(){
			$WPQore_CacheAssistanceStatus = "";
			$WPQore_CacheAssistanceNewPost = "";
			$WPQore_CacheAssistanceTimeOut = "";
			$WPQore_CacheAssistanceStatus = isset($this->options->WPQore_CacheAssistanceStatus) ? 'checked="checked"' : "";
			$WPQore_CacheAssistanceNewPost = isset($this->options->WPQore_CacheAssistanceNewPost) ? 'checked="checked"' : "";
			$WPQore_CacheAssistanceTimeOut = isset($this->cronJobSettings["period"]) ? $this->cronJobSettings["period"] : "";
?>

<div class="wrap">
<div id="icon-options-general" class="icon32"><br></div><h2>Cache Assistance</h2>
				
	<?php if($this->systemMessage){ ?>
		<div class="updated <?php echo $this->systemMessage[1]; ?>" id="message"><p><?php echo $this->systemMessage[0]; ?></p></div>
	<?php } ?>
				
<form method="post" name="wp_manager"><!-- General Options : begin -->
<input type="hidden" value="options" name="WPQore_CacheAssistancePage">

<div id="poststuff">
<div class="postbox">
<table class="form-table">
<tbody>

<h3>General Options</h3>

<tr valign="top">
<th scope="row"><label for="home"><b>Cache Frontend</b></label></th>
<td>
<label for="WPQore_CacheAssistanceStatus">
<div class="switch toggle3">
<input type="checkbox" <?php echo $WPQore_CacheAssistanceStatus; ?> id="WPQore_CacheAssistanceStatus" name="WPQore_CacheAssistanceStatus">
<label><i></i></label>
</div>
<i>Turn 'On' to enable</i><br>
This will cache all posts and pages. Enabling cache will help serve your web pages quicker to your users and overall increase sitespeed.
</label>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><b>New Post or Page</b></label></th>
<td>
<label for="WPQore_CacheAssistanceNewPost">
<div class="switch toggle3">
<input type="checkbox" <?php echo $WPQore_CacheAssistanceNewPost; ?> id="WPQore_CacheAssistanceNewPost" name="WPQore_CacheAssistanceNewPost">
<label><i></i></label>
</div>
<i>Turn 'On' to enable</i><br>
Clear all cache files when a post or page is published.
</label>
</td>
</tr>
	
</tbody>
</table>	
</div>
</div>	

<p style="border-bottom: 1px dashed #CCCCCC;padding-bottom: 20px">
<input type="submit" value="Save changes" class="button-primary">
</p>

</form><!-- General Options : end -->


<form method="post" name="wp_manager"><!-- Clear Cache : begin -->
<input type="hidden" value="deleteCache" name="WPQore_CacheAssistancePage">

<div id="poststuff">
<div class="postbox">
<table class="form-table">
<tbody>

<h3>Delete Cache</h3>

<tr valign="top">
<th scope="row"><label for="home"><b>Clear all cache</b></label></th>
<td>
<label for="WPQore_Delete_All_Cache">
<i>Target folder:</i><br>

<pre style="margin-top:10px;background:#FFFFFF;padding:10px;border: 1px dashed #CCCCCC;">
<b><?php echo $this->WPconDIR; ?>/cache/all</b>
</pre>

</label>
</td>
</tr>
	
</tbody>
</table>	
</div>
</div>	

<p style="border-bottom: 1px dashed #CCCCCC;padding-bottom: 20px">
<input type="submit" value="Delete Now" class="button-primary">
</p>

</form><!-- Clear Cache : end -->
				
</div>

<style>
th, td {
border-left: 1px solid #e1e1e1;
border-right: 1px solid #e1e1e1;
border-top: 1px solid #e1e1e1;
}
.form-table{margin-top: 0px;}
</style>

<?php }

		public function checkCronTime(){
			add_action($this->slug,  array($this, 'setSchedule'));
			add_action($this->slug."TmpDelete",  array($this, 'actionDelete'));
		}

		public function detectNewPost(){
			if(isset($this->options->WPQore_CacheAssistanceNewPost) && isset($this->options->WPQore_CacheAssistanceStatus)){
				add_filter ('publish_post', array($this, 'deleteCache'));
				add_filter ('delete_post', array($this, 'deleteCache'));
			}
		}

		public function deleteCache(){
			if(is_dir($this->WPconDIR."/cache/all")){
				//$this->rm_folder_recursively($this->WPconDIR."/cache/all");
				if(is_dir($this->WPconDIR."/cache/tmpWPQca")){
					rename($this->WPconDIR."/cache/all", $this->WPconDIR."/cache/tmpWPQca/".time());
					wp_schedule_single_event(time() + 60, $this->slug."TmpDelete");
					$this->systemMessage = array("All cache files have been deleted","success");
				}else if(@mkdir($this->WPconDIR."/cache/tmpWPQca", 0755, true)){
					rename($this->WPconDIR."/cache/all", $this->WPconDIR."/cache/tmpWPQca/".time());
					wp_schedule_single_event(time() + 60, $this->slug."TmpDelete");
					$this->systemMessage = array("All cache files have been deleted","success");
				}else{
					$this->systemMessage = array("Permission of <strong>/wp-content/cache</strong> must be <strong>755</strong>", "error");
				}
			}else{
				$this->systemMessage = array("Cache deleted","success");
			}
		}

		public function actionDelete(){
			if(is_dir($this->WPconDIR."/cache/tmpWPQca")){
				$this->rm_folder_recursively($this->WPconDIR."/cache/tmpWPQca");
				if(is_dir($this->WPconDIR."/cache/tmpWPQca")){
					wp_schedule_single_event(time() + 60, $this->slug."TmpDelete");
				}
			}
		}
		
		public function addCacheTimeout(){
			if(isset($_POST["WPQore_CacheAssistanceTimeOut"])){
				if($_POST["WPQore_CacheAssistanceTimeOut"]){
					wp_clear_scheduled_hook($this->slug);
					wp_schedule_event(time() + 120, $_POST["WPQore_CacheAssistanceTimeOut"], $this->slug);
				}else{
					wp_clear_scheduled_hook($this->slug);
				}
			}
		}

		public function setSchedule(){
			$this->deleteCache();
		}

		public function setCronJobSettings(){
			if(wp_next_scheduled($this->slug)){
				$this->cronJobSettings["period"] = wp_get_schedule($this->slug);
				$this->cronJobSettings["time"] = wp_next_scheduled($this->slug);
			}
		}		

		public function rm_folder_recursively($dir, $i = 1) {
		    foreach(scandir($dir) as $file) {
		    	if($i > 500){
		    		return true;
		    	}else{
		    		$i++;
		    	}
		        if ('.' === $file || '..' === $file) continue;
		        if (is_dir("$dir/$file")) $this->rm_folder_recursively("$dir/$file", $i);
		        else unlink("$dir/$file");
		    }
		    
		    rmdir($dir);
		    return true;
		}

		public function saveOption(){
			unset($_POST["WPQore_CacheAssistancePage"]);
			$data = json_encode($_POST);
			//for optionsPage() $_POST is array and json_decode() converts to stdObj
			$this->options = json_decode($data);

			if(get_option("Cache_Assistance")){
				update_option("Cache_Assistance", $data);
			}else{
				add_option("Cache_Assistance", $data, null, "yes");
			}
			$this->systemMessage = $this->modifyHtaccess($_POST);
		}

		public function setOptions(){
			if($data = get_option("Cache_Assistance")){
				$this->options = json_decode($data);
			}
		}

		public function modifyHtaccess($post){
			if(isset($post["WPQore_CacheAssistanceStatus"]) && $post["WPQore_CacheAssistanceStatus"] == "on"){
				if(!is_file(ABSPATH.".htaccess")){
					return array(".htacces was not found", "error");
				}else if(is_writable(ABSPATH.".htaccess")){
					$htaccess = file_get_contents(ABSPATH.".htaccess");
					$htaccess = $this->insertRewriteRule($htaccess);
					$this->insertGzipRule($htaccess, $post);
				}else{
					return array(".htacces is not writable", "error");
				}
				return array("Options saved", "success");
			}else{
				//disable
				$this->deleteCache();
				return array("Options saved", "success");
			}
		}

		public function insertGzipRule($htaccess, $post){
			if(isset($post["WPQore_CacheAssistanceGzip"]) && $post["WPQore_CacheAssistanceGzip"] == "on"){

		    	$data = "# BEGIN GzipCache_Assistance"."\n".
		          		"<IfModule mod_deflate.c>"."\n".
		  				"AddOutputFilterByType DEFLATE text/plain"."\n".
		  				"AddOutputFilterByType DEFLATE text/html"."\n".
		  				"AddOutputFilterByType DEFLATE text/xml"."\n".
		  				"AddOutputFilterByType DEFLATE text/css"."\n".
		  				"AddOutputFilterByType DEFLATE application/xml"."\n".
		  				"AddOutputFilterByType DEFLATE application/xhtml+xml"."\n".
		  				"AddOutputFilterByType DEFLATE application/rss+xml"."\n".
		  				"AddOutputFilterByType DEFLATE application/javascript"."\n".
		  				"AddOutputFilterByType DEFLATE application/x-javascript"."\n".
		  				"</IfModule>"."\n".
						"# END GzipCache_Assistance"."\n\n";

				preg_match("/BEGIN GzipCache_Assistance/", $htaccess, $check);
				if(count($check) === 0){
					file_put_contents(ABSPATH.".htaccess", $data.$htaccess);
				}else{
					//already changed
				}	

			}else{

				//delete gzip rules
				$htaccess = preg_replace("/#\s?BEGIN\s?GzipCache_Assistance.*?#\s?END\s?GzipCache_Assistance/s", "", $htaccess);

				//echo $htaccess;
				file_put_contents(ABSPATH.".htaccess", $htaccess);
			}
		}
		
		public function insertRewriteRule($htaccess){
			preg_match("/wp-content\/cache\/all/", $htaccess, $check);
			if(count($check) === 0){
				$htaccess = $this->getHtaccess().$htaccess;
			}else{
				//already changed
			}
			return $htaccess;
		}

		public function getHtaccess(){
			$data = "# BEGIN Cache_Assistance"."\n".
					"<IfModule mod_rewrite.c>"."\n".
					"RewriteEngine On"."\n".
					"RewriteBase /"."\n".
					"RewriteCond %{REQUEST_METHOD} !POST"."\n".
					"RewriteCond %{QUERY_STRING} !.*=.*"."\n".
					"RewriteCond %{HTTP:Cookie} !^.*(comment_author_|wordpress_logged_in|wp-postpass_).*$"."\n".
					'RewriteCond %{HTTP:X-Wap-Profile} !^[a-z0-9\"]+ [NC]'."\n".
					'RewriteCond %{HTTP:Profile} !^[a-z0-9\"]+ [NC]'."\n".
					"RewriteCond %{DOCUMENT_ROOT}/".$this->getRewriteBase()."wp-content/cache/all/".$this->getRewriteBase()."$1/index.html -f"."\n".
					'RewriteRule ^(.*) "/'.$this->getRewriteBase().'wp-content/cache/all/'.$this->getRewriteBase().'$1/index.html" [L]'."\n".
					"</IfModule>"."\n".
					"# END Cache_Assistance"."\n";
			return $data;
		}

		public function getRewriteBase(){
			$tmp = str_replace($_SERVER['DOCUMENT_ROOT']."/", "", ABSPATH);
			$tmp = str_replace("/", "", $tmp);
			$tmp = $tmp ? $tmp."/" : "";
			return $tmp;
		}

		public function doCache(){
			if(isset($this->options->WPQore_CacheAssistanceStatus)){
				$this->startTime = microtime(true);
				ob_start(array($this, "callback"));
			}
		}

		public function ignored(){
			$ignored = array("robots.txt", "wp-login.php", "wp-cron.php", "wp-content", "wp-admin", "wp-includes");
			foreach ($ignored as $key => $value) {
				if (strpos($_SERVER["REQUEST_URI"], $value) === false) {
				}else{
					return true;
				}
			}
			return false;
		}

		public function callback($buffer){
			$buffer = $this->checkShortCode($buffer);

			if(defined('DONOTCACHEPAGE')){ // for Wordfence: not to cache 503 pages
				return $buffer;
			}else if(is_404()){
				return $buffer;
			}else if($this->ignored()){
				return $buffer;
			}else if($this->blockCache === true){
				return $buffer."<!-- not cached -->";
			}else if(isset($_GET["preview"])){
				return $buffer."<!-- not cached -->";
			}else if($this->checkHtml($buffer)){
				return $buffer;
			}else{
				$cachFilePath = $this->WPconDIR."/cache/all".$_SERVER["REQUEST_URI"];
				$content = $this->cacheDate($buffer);
				$this->createFolder($cachFilePath, $content);

				return $buffer;
			}
		}

		public function checkHtml($buffer){
			preg_match('/<\/html>/', $buffer, $htmlTag);
			preg_match('/<\/body>/', $buffer, $bodyTag);
			if(count($htmlTag) > 0 && count($bodyTag) > 0){
				return 0;
			}else{
				return 1;
			}
		}

		public function cacheDate($buffer){
			return $buffer."<!-- Cache Assistance generated this file in ".$this->creationTime()." seconds, on ".date("d-m-y G:i:s")." -->";
		}

		public function creationTime(){
			return microtime(true) - $this->startTime;
		}

		public function isCommenter(){
			$commenter = wp_get_current_commenter();
			return isset($commenter["comment_author_email"]) && $commenter["comment_author_email"] ? false : true;
		}

		public function createFolder($cachFilePath, $buffer, $extension = "html"){
			if($buffer && strlen($buffer) > 100){
				if (!is_user_logged_in() && $this->isCommenter()){
					if(!is_dir($cachFilePath)){
						if(is_writable($this->WPconDIR) || ((is_dir($this->WPconDIR."/cache")) && (is_writable($this->WPconDIR."/cache")))){
							if (!mkdir($cachFilePath, 0755, true)){

							}else{
								file_put_contents($cachFilePath."/index.".$extension, $buffer);
							}
						}else{

						}
					}else{
						if(file_exists($cachFilePath."/index.".$extension)){

						}else{
							file_put_contents($cachFilePath."/index.".$extension, $buffer);
						}
					}
				}
			}
		}
	}
?>
