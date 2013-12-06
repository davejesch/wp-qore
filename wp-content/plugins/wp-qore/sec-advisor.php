<?php

// Security check
if ( ! class_exists('WP') ) {
	die();
}

// AntiSEC_Threat class
class AntiSEC_Threat {

	// Save me
	private static $base;

    // Pseudo-constructor class
	public static function instance(){
		new self();
	}

	// The constructor class
	public function __construct(){

		// AUTO SAVE
		if ( ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) OR ( defined('XMLRPC_REQUEST') && XMLRPC_REQUEST ) ) {
			return;
		}

		// Plugin Base
		self::$base = plugin_basename(__FILE__);

		// Cronjob
		if ( defined('DOING_CRON') ) {

			add_action(
				'sec-advisor_daily_cronjob',
				array(
					__CLASS__,
					'do_daily_cronjob'
				)
			);

		// Admin 
		} elseif ( is_admin() ) {

			// AJAX
			if ( defined('DOING_AJAX') ) {
				add_action(
					'wp_ajax_get_ajax_response',
					array(
						__CLASS__,
						'get_ajax_response'
					)
				);

			// Backend
			} else {

				// Actions
				add_action(
					'admin_menu',
					array(
						__CLASS__,
						'add_sidebar_menu'
					)
				);

				add_action(
					'admin_bar_menu',
					array(
						__CLASS__,
						'add_adminbar_menu'
					),
					91
				);

				add_action(
					'admin_notices',
					array(
						__CLASS__,
						'show_dashboard_notice'
					)
				);

				add_action(
					'admin_print_styles',
					array(
						__CLASS__,
						'add_enqueue_style'
					)
				);

				// GUI
				if ( self::_is_current_page('home') ) {
					add_action(
						'admin_print_scripts',
						array(
							__CLASS__,
							'add_enqueue_script'
						)
					);

				// Plugins
				} else if ( self::_is_current_page('plugins') ) {
					add_action(
						'deactivate_' .self::$base,
						array(
							__CLASS__,
							'clear_scheduled_hook'
						)
					);

					add_filter(
						'plugin_row_meta',
						array(
							__CLASS__,
							'init_row_meta'
						),
						10,
						2
					);

					add_filter(
						'plugin_action_links_' .self::$base,
						array(
							__CLASS__,
							'init_action_links'
						)
					);
				}
			}
		}
	}

    // Adding the Action Links
	public static function init_action_links($data){

		// Permissions
		if ( ! current_user_can('manage_options') ) {
			return $data;
		}

		return array_merge(
			$data,
			array(
				sprintf(
					'<a href="%s">%s</a>',
					add_query_arg(
						array(
							'page' => 'sec-advisor'
						),

						admin_url('options-general.php')
					),
					__('Settings')
				)
			)
		);
	}

    // Links to the plug-in management
	public static function init_row_meta($data, $page) {
		if ( $page != self::$base ) {
			return $data;
		}

		return array_merge(
			$data, array( '', '' )
		);
	}

    // Action on activation of the plugin
	public static function install(){

		// Option array
		add_option(
			'sec-advisor',
			array(),
			'',
			'no'
		);

		// Enable cronjob
		if ( self::_get_option('cronjob_enable') ) {
			self::_add_scheduled_hook();
		}
	}

    // Actions when deactivating the plugin
	public static function deactivation(){
		self::clear_scheduled_hook();
	}

    // Uninstallation Plugins
	public static function uninstall(){

		// Global
		global $wpdb;

		// Remove settings
		delete_option('sec-advisor');
	}

    // Returns a radio button
	private static function _get_option($field){
		$options = wp_parse_args(
			get_option('sec-advisor'),
			array(
				'cronjob_enable' => 0,
				'cronjob_alert'  => 0,
				'safe_browsing'  => 0,
				'notify_email'   => '',
				'white_list'     => ''
			)
		);

		return ( empty($options[$field]) ? '' : $options[$field] );
	}

    // Updated a radio button
	private static function _update_option($field, $value){
		self::_update_options(
			array(
				$field => $value
			)
		);
	}

    // Updated multiple radio buttons
	private static function _update_options($data){
		update_option(
			'sec-advisor',
			array_merge(
				(array)get_option('sec-advisor'),
				$data
			)
		);
	}

    // Initialization of the cron jobs
	private static function _add_scheduled_hook(){
		if ( ! wp_next_scheduled('sec-advisor_daily_cronjob') ) {
			wp_schedule_event(
				time(),
				'daily',
				'sec-advisor_daily_cronjob'
			);
		}
	}

    // Termination of cronjobs
	public static function clear_scheduled_hook(){
		if ( wp_next_scheduled('sec-advisor_daily_cronjob') ) {
			wp_clear_scheduled_hook('sec-advisor_daily_cronjob');
		}
	}

    // Execution of cron jobs
	public static function do_daily_cronjob(){

		// No cron job
		if ( ! self::_get_option('cronjob_enable') ) {
			return;
		}

		// Safe Browsing API
		self::_check_safe_browsing();

		// Theme & Permalinks
		self::_check_blog_internals();
	}

	// Does the Safe Browsing check
	private static function _check_safe_browsing(){

		// Not enabled
		if ( ! self::_get_option('safe_browsing') ) {
			return;
		}

		// Start API request
		$response = wp_remote_get(
			sprintf( 
				'https://sb-ssl.google.com/safebrowsing/api/lookup?client=api&apikey=%s&appver=0.1&pver=3.0&url=%s',
				'ABQIAAAAM6ACuUK0i-GaYGBgcPM-JRSnu2A5lr2Wpsrzaer94rqSDKoE6A', /* API Key for AntiSEC_Threat */
				urlencode( get_bloginfo('url') )
			),
			array(
				'sslverify' => false
			)
		);

		// API Error
		if ( is_wp_error($response) ) {
			return;
		}

		// All clear
		if ( wp_remote_retrieve_response_code($response) == 204 ) {
			return;
		}

		// Send notification
		self::_send_warning_notification(
			esc_html__('Safe Browsing Alert', 'sec-advisor'),
			sprintf(
				"%s\r\nhttp://www.google.com/safebrowsing/diagnostic?site=%s&hl=%s",
				esc_html__('Please check the Google Safe Browsing diagnostic page:', 'sec-advisor'),
				urlencode( get_bloginfo('url') ),
				substr(get_locale(), 0, 2)
			)
		);
	}

    // Executes the internal audit
	private static function _check_blog_internals(){

		// Execute checks
		if ( ! self::_check_theme_files() && ! self::_check_permalink_structure() ) {
			return;
		}

		// Send notification
		self::_send_warning_notification(
			esc_html__('Potential security threat suspected', 'sec-advisor'),
			sprintf(
				"%s\r\n%s",
				esc_html__('The daily security scan of your site suspects a potential threat.', 'sec-advisor'),
				get_bloginfo('url')
			)
		);

		// Store alert
		self::_update_option(
			'cronjob_alert',
			1
		);
	}

    // Prepares the notification message
	private static function _send_warning_notification($subject, $body){

		// Receiver email address
		if ( $email = self::_get_option('notify_email') ) {
			$email = sanitize_email($email);
		} else {

			$email = get_bloginfo('admin_email');
		}

		// Send notification email
		wp_mail(
			$email,
			sprintf(
				'[%s] %s',
				get_bloginfo('name'),
				$subject
			),

			sprintf(
				"%s\r\n\r\n\r\n%s\r\n%s\r\n",
				$body,
				esc_html__('Notification message from the WP Qore Security Advisor', 'sec-advisor'),
				esc_html__('http://wpqore.com', 'sec-advisor')
			)
		);
	}

    // Initialization of the GUI
    public static function add_sidebar_menu(){

       // Create a submenu within WP Qore
       add_submenu_page( 
	       'wp-qore/functions.php', 
		   'Security Advisor', 
		   'Security Advisor', 
		   'manage_options', 
		   'sec-advisor', 
			array(
				__CLASS__,
				'show_admin_menu'
			)
		);
	}

    // Initialization of JavaScript
    public static function add_enqueue_script(){

		// Learn to read
		$data = get_plugin_data(__FILE__);

		// Embed JS
		wp_register_script(
			'av_script',
			plugins_url('js/sec-advisor.js', __FILE__),
			array('jquery'),
			$data['Version']
		);

		// Embed scripts
		wp_enqueue_script('av_script');

		// Locate scripts
		wp_localize_script(
			'av_script',
			'av_settings',

			array(
				'nonce' => wp_create_nonce('av_ajax_nonce'),
				'ajax' 	=> admin_url('admin-ajax.php'),
				'theme'	=> urlencode(self::_get_theme_name()),
				'msg_1'	=> esc_html__('There is no threats', 'sec-advisor'),
				'msg_2' => esc_html__('View line', 'sec-advisor'),
				'msg_3' => esc_html__('Scan completed', 'sec-advisor')
			)
		);
	}

    // Initialization of style sheets
    public static function add_enqueue_style(){

		// Learn to read
		$data = get_plugin_data(__FILE__);

		// CSS register
		wp_register_style(
			'av_css',
			plugins_url('css/sec-advisor.css', __FILE__),
			array(),
			$data['Version']
		);

		// Embed CSS
		wp_enqueue_style('av_css');
	}

    // Return the current theme
    private static function _get_current_theme(){

		// Since WP 3.4
		if ( function_exists('wp_get_theme') ) {

			// Init
			$theme = wp_get_theme();
			$name = $theme->get('Name');
			$slug = $theme->get_stylesheet();
			$files = $theme->get_files('php', 1);

			// Read
			if ( empty($name) OR empty($files) ) {
				return false;
			}

			// Return
			return array(
				'Name' => $name,
				'Slug' => $slug,
				'Template Files' => $files
			);

		} else {

			if ( $themes = get_themes() ) {

				// Current theme
				if ( $theme = get_current_theme() ) {

					if ( array_key_exists((string)$theme, $themes) ) {
						return $themes[$theme];
					}
				}
			}
		}

		return false;
	}

    // Return of files in the current theme
    private static function _get_theme_files(){

		// Theme available
		if ( ! $theme = self::_get_current_theme() ) {
			return false;
		}

		// No files
		if ( empty($theme['Template Files']) ) {
			return false;
		}

		// Return
		return array_unique(
			array_map(
				create_function(
					'$v',
					'return str_replace(array(WP_CONTENT_DIR, "wp-content"), "", $v);'
				),
				$theme['Template Files']
			)
		);
	}

    // Return the name of the current theme
	private static function _get_theme_name(){

		if ( $theme = self::_get_current_theme() ) {

			if ( ! empty($theme['Slug']) ) {
				return $theme['Slug'];
			}

			if ( ! empty($theme['Name']) ) {
				return $theme['Name'];
			}
		}

		return false;
	}

    // Return the whitelist
    private static function _get_white_list(){
		return explode(
			':',
			self::_get_option('white_list')
		);
	}

    // Execution of AJAX
    public static function get_ajax_response(){

		// Check Referer
		check_ajax_referer('av_ajax_nonce');

		// Further testing
		if ( empty($_POST['_action_request']) ) {
			exit();
		}

		// Init
		$values = array();
		$output = '';

		// Start output
		switch ($_POST['_action_request']) {
			case 'get_theme_files':
				self::_update_option(
					'cronjob_alert',
					0
				);

				$values = self::_get_theme_files();
			break;

			case 'check_theme_file':
				
				if ( ! empty($_POST['_theme_file']) && $lines = self::_check_theme_file($_POST['_theme_file']) ) {
					foreach( $lines as $num => $line ) {
						foreach( $line as $string ) {
							$values[] = $num;
							$values[] = htmlentities($string, ENT_QUOTES);
							$values[] = md5($num . $string);
						}
					}
				}

			break;

			case 'update_white_list':

				if ( ! empty($_POST['_file_md5']) ) {
					self::_update_option(
						'white_list',
						implode(
							':',
							array_unique(
								array_merge(
									self::_get_white_list(),
									array($_POST['_file_md5'])
								)
							)
						)
					);

					$values = array($_POST['_file_md5']);
				}

				break;

			default:

				break;
		}

		// Start output
		if ( $values ) {
			$output = sprintf(
				"['%s']",
				implode("', '", $values)
			);

			// Send header
			header('Content-Type: plain/text');

			// Nonce
			echo sprintf(
				'{data:%s, nonce:"%s"}',
				$output,
				$_POST['_ajax_nonce']
			);
		}

		// Exit
		exit();
	}

    // Return the file contents
    private static function _get_file_content($file){
		return file(WP_CONTENT_DIR . $file);
	}

    // Reduction of a string
    private static function _get_dotted_line($line, $tag, $max = 100){

		// No value
		if ( ! $line OR ! $tag ) {
			return false;
		}

		// Calculate the difference
		if ( strlen($tag) > $max ) {
			return $tag;
		}

		$left = round( ($max - strlen($tag)) / 2 );

		// Convert value
		$tag = preg_quote($tag);

		// Shorten string
		$output = preg_replace(
			'/(' .$tag. ')(.{' .$left. '}).{0,}$/',
			'$1$2 ...',
			$line
		);

		$output = preg_replace(
			'/^.{0,}(.{' .$left. ',})(' .$tag. ')/',
			'... $1$2',
			$output
		);

		return $output;
	}

    // List of potentially vulnerable expressions
    private static function _php_match_pattern(){
		return '/(assert|file_get_contents|curl_exec|popen|proc_open|unserialize|eval|base64_encode|base64_decode|create_function|exec|shell_exec|system|passthru|ob_get_contents|file|curl_init|readfile|fopen|fsockopen|pfsockopen|fclose|fread|file_put_contents)\s*?\(/';
	}

    // Check file line
    private static function _check_file_line($line = '', $num){

		// Trim value
		$line = trim((string)$line);

		// Empty values
		if ( ! $line OR ! isset($num) ) {
			return false;
		}

		// Initialize values
		$results = array();
		$output = array();

		// Search commands
		preg_match_all(
			self::_php_match_pattern(),
			$line,
			$matches
		);

		// Save the result
		if ( $matches[1] ) {
			$results = $matches[1];
		}

		// Search Base64
		preg_match_all(
			'/[\'\"\$\\ \/]*?([a-zA-Z0-9]{' .strlen(base64_encode('sergej + swetlana = love.')). ',})/',
			$line,
			$matches
		);

		// Save the result
		if ( $matches[1] ) {
			$results = array_merge($results, $matches[1]);
		}

		// Search frames
		preg_match_all(
			'/<\s*?(i?frame)/',
			$line,
			$matches
		);

		// Save the result
		if ( $matches[1] ) {
			$results = array_merge($results, $matches[1]);
		}

		// Option search
		preg_match(
			'/get_option\s*\(\s*[\'"](.*?)[\'"]\s*\)/',
			$line,
			$matches
		);

		// Option check
		if ( $matches && $matches[1] && self::_check_file_line(get_option($matches[1]), $num) ) {
			array_push($results, 'get_option');
		}

		// Results
		if ( $results ) {

			// No duplicates
			$results = array_unique($results);

			// Whitelist
			$md5 = self::_get_white_list();

			// Results loop
			foreach( $results as $tag ) {
				$string = str_replace(
					$tag,
					'@span@' .$tag. '@/span@',
					self::_get_dotted_line($line, $tag)
				);

				// In whitelist
				if ( ! in_array(md5($num . $string), $md5) ) {
					$output[] = $string;
				}
			}

			return $output;
		}

		return false;
	}



	// Verify files in current theme
	private static function _check_theme_files(){

		// Files available
		if ( ! $files = self::_get_theme_files() ) {
			return false;
		}

		// Init
		$results = array();

		// Files loop
		foreach( $files as $file ) {
			if ( $result = self::_check_theme_file($file) ) {
				$results[$file] = $result;
			}
		}

		// Values available
		if ( ! empty($results) ) {
			return $results;
		}

		return false;
	}



    // Scan a file
    private static function _check_theme_file($file){

		// No file
		if ( ! $file ) {
			return false;
		}

		// Read content
		if ( ! $content = self::_get_file_content($file) ) {
			return false;
		}

		// Init
		$results = array();

		// Lines loop
		foreach( $content as $num => $line ) {
			if ( $result = self::_check_file_line($line, $num) ) {
				$results[$num] = $result;
			}
		}

		// If values exist
		if ( ! empty($results) ) {
			return $results;
		}

		return false;
	}

	// Check permalinks
	private static function _check_permalink_structure(){

		if ( $structure = get_option('permalink_structure') ) {

			// Search commands
			preg_match_all(
				self::_php_match_pattern(),
				$structure,
				$matches
			);

			// Save the result
			if ( $matches[1] ) {
				return $matches[1];
			}
		}

		return false;
	}



    // Check of the admin page
	private static function _is_current_page($page){

		switch($page) {
			case 'home':

				return ( !empty($_REQUEST['page']) && $_REQUEST['page'] == 'sec-advisor' );

			case 'index':
				
			case 'plugins':

				return ( !empty($GLOBALS['pagenow']) && $GLOBALS['pagenow'] == sprintf('%s.php', $page) );

			default:

				return false;
		}
	}

	// Display dashboard reference
	public static function show_dashboard_notice() {

		/// No alert
		if ( ! self::_get_option('cronjob_alert') ) {
			return;
		}

		// If is Adminbar
		if ( function_exists('is_admin_bar_showing') && is_admin_bar_showing() ) {
			return;
		}

		// Warning
		echo sprintf(
			'<div class="updated fade"><p><strong>%1$s:</strong> %2$s <a href="%3$s">%4$s &rarr;</a></p></div>',
			esc_html__('Security threat suspected', 'sec-advisor'),
			esc_html__('The daily security scan of your site suspects a potential threat.', 'sec-advisor'),
			add_query_arg(
				array(
					'page' => 'sec-advisor'
				),
				admin_url('options-general.php')
			),
			esc_html__('Manual scan', 'sec-advisor')
		);
	}

    // Display the menu in Adminbar
	public static function add_adminbar_menu($wp_admin_bar) {

		// No alert
		if ( ! self::_get_option('cronjob_alert') ) {
			return;
		}

		// No Adminbar
		if ( ! function_exists('is_admin_bar_showing') OR ! is_admin_bar_showing() ) {
			return;
		}
	}

    // Display the GUI
	public static function show_admin_menu() {

		// Store updates
		if ( ! empty($_POST) ) {

			// Check referer
			check_admin_referer('sec-advisor');

			// Assign values
			$options = array(
				'cronjob_enable' => (int)(!empty($_POST['av_cronjob_enable'])),
				'notify_email'	 => is_email(@$_POST['av_notify_email']),
				'safe_browsing'  => (int)(!empty($_POST['av_safe_browsing']))
			);

			// No Cronjob
			if ( empty($options['cronjob_enable']) ) {
				$options['notify_email'] = '';
				$options['safe_browsing'] = 0;
			}

			// Cronjob stop
			if ( $options['cronjob_enable'] && ! self::_get_option('cronjob_enable') ) {

				self::_add_scheduled_hook();

			} else if ( ! $options['cronjob_enable'] && self::_get_option('cronjob_enable') ) {

				self::clear_scheduled_hook();

			}

			// Save options
			self::_update_options($options); ?>
			<div id="message" class="updated fade">
				<p>
					<strong>
						<?php _e('Settings saved.') ?>
					</strong>
				</p>
			</div>
		<?php } ?>
			<div class="wrap" id="av_main">
			<div id="av_manual" style="float:right">
			    <span class="alert"></span>
			</div>

			<div class="icon32"><br></div><h2>Security Advisor</h2>
			<form method="post" action="">
			
				<?php wp_nonce_field('sec-advisor') ?>
				
			    <div id="poststuff">
				<div class="postbox">
				<table class="form-table">
				<tbody>
					<h3><?php esc_html_e('Manual Scan', 'sec-advisor') ?></h3>
				    <tr valign="top">
					<th scope="row"><label for="home"><b>Check for Threats</b></label></th>
					<td>
					<div class="inside" id="av_manual">
							<p>
								<a href="#" class="button rbutton"><?php esc_html_e('Start Scanning', 'sec-advisor') ?></a>						
							</p>
					</div>
					</td>						
					</tr>					
				</tbody>
				</table>

				<div class="inside" id="av_manual">
					<div class="output">Security Advisor offers your WordPress website protection from security threats, such as: virus, malicious code, and security exploits.</div>
				</div>
				</div>
				</div>		

                <p style="border-bottom: 1px dashed #CCCCCC;padding-top: 10px"></p>				

				<div id="poststuff">
				<div class="postbox">
				<table class="form-table">
				<tbody>
				    <h3><?php esc_html_e('Automatic Scan', 'sec-advisor') ?></h3>
					<tr valign="top">
					<th scope="row"><label for="home"><b>Daily Scanning</b></label></th>
					<td>
					<label for="av_cronjob_enable">
					<div class="switch toggle3">
					<input type="checkbox" name="av_cronjob_enable" id="av_cronjob_enable" value="1" <?php checked(self::_get_option('cronjob_enable'), 1) ?> />
					<label><i></i></label>
					</div>
					<div style="padding-top:8px">
					<small>
					<?php 
					if ( $timestamp = wp_next_scheduled('sec-advisor_daily_cronjob') ) {
					echo sprintf(
					'%s: <span style="color:#FF0000;font-weight:bold">%s</span>',
					esc_html__('Next check', 'sec-advisor'),
					date_i18n('m/d/Y @ H:i:s', $timestamp + get_option('gmt_offset') * 3600)
					);
					} 
					?>
					</small>
					</div>
					</label>
					</td>
					</tr>

					<tr valign="top">
					<th scope="row"><label for="home"><b>Alternate E-mail</b></label></th>
					<td>
					<label for="av_notify_email">
					<input type="text" name="av_notify_email" id="av_notify_email" value="<?php esc_attr_e(self::_get_option('notify_email')) ?>" class="regular-text" />
					<br>
					<small>If this field is empty, the admin e-mail will be notified. Daily scanning must be 'On' to use this feature.</small>
					</label>
					</td>
					</tr>

					<tr>
						<td></td>
					<td>
					<table>
					<tr>
					<td>
					<input type="checkbox" name="av_safe_browsing" id="av_safe_browsing" value="1" <?php checked(self::_get_option('safe_browsing'), 1) ?> />
					</td>
					<td>
					<label for="av_safe_browsing">

					<?php 
					echo sprintf(
					esc_html__('Check to enable %s (Recommended)', 'sec-advisor'),
					'<a href="https://developers.google.com/safe-browsing/" target="_blank">Google Safe Browsing</a>'
					) 
					?>
					</label>
					</td>
                                        </tr>
					</table>
					
					</td>
					</tr>
					</tbody>
					</table>

					</div>
					</div>

		    <p>
		        <input type="submit" class="button button-primary" value="<?php _e('Save Changes') ?>" />
                    </p>

            </form>	
		</div>

<?php } }

// Load Instance
add_action(
	'plugins_loaded',
	array(
		'AntiSEC_Threat',
		'instance'
	),
	99
);

?>
