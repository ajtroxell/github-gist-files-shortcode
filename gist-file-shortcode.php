<?php
/*
Plugin Name: GitHub Gist Files Shortcode
Plugin URI: http://www.ajtroxell.com/github-gist-files-shortcode-plugin
Description: Easily insert specific GitHub Gist files with this shortcode [gist id="xxxxxx" file="name"]
Version: 2.0
Author: AJ Troxell
Author URI: http://www.ajtroxell.com/
*/
 
/*
 * USAGE:
 * Three ways are provided to insert the shortcode. The value "xxxxxx" represents your Gist ID, and "name" is the filename of the file within the Gist.
 * Insert [gist id="xxxxxx" file="name"] manually.
 * By using the HTML Editor shortcode button.
 * By pressing ctrl+alt+g.
 * You can place these shortcodes in pages, posts or any custom content.
 *
 * INTALLATION
 * Unzip gist-file-shortcode.zip and upload the gist-file-shortcode folder to / wp-content/plugins/.
 * On the Plugins > Installed Plugins page, activate the GitHub Gist Files Shortcode plugin.
 *
 * LICENSE
 * Released under the GPLv2 or later.
 */

	// Check for updates
	require 'plugin-updates/plugin-update-checker.php';
		$gist_files_shortcode_update = new PluginUpdateChecker(
	    'http://labs.ajtroxell.com/plugins/github-gist-files-shortcode/info.json',
	    __FILE__,
	    'github-gist-files-shortcode'
	);


	// Main Function
	function gist_files_shortcode($atts, $content = null) {
	 
		extract(shortcode_atts(array(
			'id' => '',
			'file' => ''
		), $atts));
	 
		$output =  '<script src="http://gist.github.com/'.trim($id).'.js?file='.$file.'"></script>';
		
		if($content != null){
			$output = $output.'<noscript><pre>'.$content.'</pre></noscript>';
		}
		
		return $output;
		
	}
 
	// Create Shortcode
	add_shortcode('gist', 'gist_files_shortcode');

	// Hotkey Function
	function gist_files_shortcode_scripts($hook) {
	 
		if( $hook != 'edit.php' && $hook != 'post.php' && $hook != 'post-new.php' ) 
			return;
	 
		wp_enqueue_script( 'jquery-hotkeys-js', plugins_url( 'gist-file-shortcode/js/jquery.hotkeys.js' , dirname(__FILE__) ) );
		wp_enqueue_script( 'jquery-function-js', plugins_url( 'gist-file-shortcode/js/jquery.function.js' , dirname(__FILE__) ) );
		wp_enqueue_script( 'jquery-shortcut-js', plugins_url( 'gist-file-shortcode/js/jquery.shortcut.js' , dirname(__FILE__) ) );
	}
	add_action('admin_enqueue_scripts', 'gist_files_shortcode_scripts');

	//Register HTML editor Button
	if( !function_exists('_add_my_quicktags') ){
		function _add_my_quicktags(){
?>
<script type="text/javascript">  
	//Add custom Quicktag button to the WordPress editor 
	QTags.addButton( 'gist', 'Gist', prompt_user );
    function prompt_user(e, c, ed) {
        gist_id = prompt('Gist ID');
        	if ( gist_id === null ) return;
        gist_file = prompt('Gist File Name');
        	if ( gist_file === null ) return;
        	
        gist_value = '[gist id="' + gist_id + '" file="' + gist_file + '"]';
        this.tagStart = gist_value;

        QTags.TagButton.prototype.callback.call(this, e, c, ed);
    }
</script>
<?php
	}
	// Attach it to 'admin_print_footer_scripts' (for admin-only)
	add_action('admin_print_footer_scripts',  '_add_my_quicktags');
	}

	function github_gist_files_shortcode_presstrends_plugin() {
	    // PressTrends Account API Key
	    $api_key = '1uv0ak16ziqw785pmqxn0eykq5pmhic3kvqv';
	    $auth    = 'k21gf5z188wtv8crxhljzzrz6emgfr6d3';
	    // Start of Metrics
	    global $wpdb;
	    $data = get_transient( 'presstrends_cache_data' );
	    if ( !$data || $data == '' ) {
	        $api_base = 'http://api.presstrends.io/index.php/api/pluginsites/update/auth/';
	        $url      = $api_base . $auth . '/api/' . $api_key . '/';
	        $count_posts    = wp_count_posts();
	        $count_pages    = wp_count_posts( 'page' );
	        $comments_count = wp_count_comments();
	        if ( function_exists( 'wp_get_theme' ) ) {
	            $theme_data = wp_get_theme();
	            $theme_name = urlencode( $theme_data->Name );
	        } else {
	            $theme_data = get_theme_data( get_stylesheet_directory() . '/style.css' );
	            $theme_name = $theme_data['Name'];
	        }
	        $plugin_name = '&';
	        foreach ( get_plugins() as $plugin_info ) {
	            $plugin_name .= $plugin_info['Name'] . '&';
	        }
	        // CHANGE __FILE__ PATH IF LOCATED OUTSIDE MAIN PLUGIN FILE
	        $plugin_data         = get_plugin_data( __FILE__ );
	        $posts_with_comments = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type='post' AND comment_count > 0" );
	        $data                = array(
	            'url'             => stripslashes( str_replace( array( 'http://', '/', ':' ), '', site_url() ) ),
	            'posts'           => $count_posts->publish,
	            'pages'           => $count_pages->publish,
	            'comments'        => $comments_count->total_comments,
	            'approved'        => $comments_count->approved,
	            'spam'            => $comments_count->spam,
	            'pingbacks'       => $wpdb->get_var( "SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_type = 'pingback'" ),
	            'post_conversion' => ( $count_posts->publish > 0 && $posts_with_comments > 0 ) ? number_format( ( $posts_with_comments / $count_posts->publish ) * 100, 0, '.', '' ) : 0,
	            'theme_version'   => $plugin_data['Version'],
	            'theme_name'      => $theme_name,
	            'site_name'       => str_replace( ' ', '', get_bloginfo( 'name' ) ),
	            'plugins'         => count( get_option( 'active_plugins' ) ),
	            'plugin'          => urlencode( $plugin_name ),
	            'wpversion'       => get_bloginfo( 'version' ),
	        );
	        foreach ( $data as $k => $v ) {
	            $url .= $k . '/' . $v . '/';
	        }
	        wp_remote_get( $url );
	        set_transient( 'presstrends_cache_data', $data, 60 * 60 * 24 );
	        }
	    }
	// PressTrends WordPress Action
	add_action('admin_init', 'github_gist_files_shortcode_presstrends_plugin');
?>