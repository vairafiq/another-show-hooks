<?php
/**
* Plugin Name: Show Hooks
* Description: Debug your code quickly by showing the origin of action and filter hooks sequentially on a page.
* Version: 0.1.4
* Author: rafiq91
* License: GPLv2 or later
* Text Domain: show-hooks
* Domain Path: /languages/
*/
/*
This program is free software originally frocked form @stuartobrien; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) or die( 'No Trespassing!' ); // Security

class ABC_Show_Hooks {
	private $status;
	private $all_hooks = array();
	private $recent_hooks = array();
	private $ignore_hooks = array();
	private $doing = 'collect';
	/**
	*  Instantiator
	*/
	public static function get_instance() {
		static $instance = null;
		if ( null === $instance ) {
			$instance = new self();
			$instance->init();
		}
		return $instance;
	}
	
	/**
	 * Construct and initialize the main plugin class
	 */
	
	public function __construct() {}
	
	function init() {
		
		// Use this to set any tags known to cause display problems.
		// Will be display in sidebar.
		$this->ignore_hooks = apply_filters( 'abc_show_hooks_ignore_hooks', array(
			'attribute_escape',
			'body_class',
			'the_post',
			'post_edit_form_tag',
			//'gettext',
		) );
		// Translations
		add_action( 'plugins_loaded', array( $this, 'load_translation' ) );
		// Set autive status property.
		$this->set_active_status();
		// Attach the hooks as on plugin init.
		$this->attach_hooks();
		// Init the plugin.
		add_action( 'init', array( $this, 'plugin_init' ) );
	}
	
	/**
	 * Helper function that sets the active status of the hooks displaying.
	 */
	public function set_active_status() {
		if ( ! isset( $this->status ) ) {
			if ( ! isset(  $_COOKIE['abc_status'] ) ) {
				setcookie( 'abc_status', 'off', time()+3600*24*100, '/' );
			}
			if ( isset( $_REQUEST['abc-hooks'] ) ) {
				setcookie( 'abc_status', sanitize_text_field( $_REQUEST['abc-hooks']), time()+3600*24*100, '/' );
				$this->status = sanitize_text_field( $_REQUEST['abc-hooks'] );
			}
			elseif ( isset( $_COOKIE['abc_status']) ) {
				$this->status = sanitize_text_field( $_COOKIE['abc_status']);
			}
			else{
				$this->status = 'off';
			}
		}
	}
	
	/**
	 * Helper function to attach the filter that render all the hook labels.
	 */
	public function attach_hooks() {
		if ( $this->status == 'show-action-hooks' || $this->status == 'show-filter-hooks' ) {
			add_filter( 'all', array( $this, 'hook_all_hooks' ), 100 );
			// add_action( 'shutdown', array( $this, 'notification_switch' ) );
			add_action( 'shutdown', array( $this, 'filter_hooks_panel' ) );
		}
	}
	
	/**
	 * Helper function to detach the filter that render all the hook labels.
	 */
	public function detach_hooks() {
		remove_filter( 'all', array( $this, 'hook_all_hooks' ), 100 );
		// remove_action( 'shutdown', array( $this, 'notification_switch' ) );
		remove_action( 'shutdown', array( $this, 'filter_hooks_panel' ) );
	}
	
	
	/*
	 * Admin Menu top bar
	 */
	function admin_bar_menu( $wp_admin_bar ) {
		// Suspend the hooks rendering.
		$this->detach_hooks();
		// Setup a base URL and clear it of the intial `abc-hooks` arg.
		$url = remove_query_arg( 'abc-hooks' );
		if ( 'show-action-hooks' == $this->status ) {
			$title 	= __( 'Stop Showing Action Hooks' , 'show-hooks' );
			$href 	= add_query_arg( 'abc-hooks', 'off', $url );
			$css 	= 'abc-hooks-on abc-hooks-normal';
		}
		else {
			$title 	= __( 'Show Action Hooks' , 'show-hooks' );
			$href 	= add_query_arg( 'abc-hooks', 'show-action-hooks', $url );
			$css 	= '';
		}
		$menu_title = __( 'Show Hooks' , 'show-hooks' );
		if ( ( 'show-action-hooks' == $this->status ) ) {
			$menu_title 	= __( 'Stop Showing Action Hooks' , 'show-hooks' );
			$href 			= add_query_arg( 'abc-hooks', 'off', $url );
		}
		if ( ( 'show-filter-hooks' == $this->status ) ) {
			$menu_title	= __( 'Stop Showing Action & Filter Hooks' , 'show-hooks' );
			$href 	= add_query_arg( 'abc-hooks', 'off', $url );
		}

		$wp_admin_bar->add_menu( array(
			'title'		=> '<span class="ab-icon"></span><span class="ab-label">' . $menu_title . '</span>',
			'id'		=> 'abc-main-menu',
			'parent'	=> false,
			'href'		=> $href,
		) );
		$wp_admin_bar->add_menu( array(
			'title'		=> $title,
			'id'		=> 'abc-simply-show-hooks',
			'parent'	=> 'abc-main-menu',
			'href'		=> $href,
			'meta'		=> array( 'class' => $css ),
		) );
		if ( $this->status=="show-filter-hooks" ) {
			$title	= __( 'Stop Showing Action & Filter Hooks' , 'show-hooks' );
			$href 	= add_query_arg( 'abc-hooks', 'off', $url );
			$css 	= 'abc-hooks-on abc-hooks-sidebar';
		}
		else {
			$title	= __( 'Show Action & Filter Hooks' , 'show-hooks' );
			$href 	= add_query_arg( 'abc-hooks', 'show-filter-hooks', $url );
			$css 	= '';
		}
		$wp_admin_bar->add_menu( array(
			'title'		=> $title,
			'id'		=> 'abc-show-all-hooks',
			'parent'	=> 'abc-main-menu',
			'href'		=> $href,
			'meta'		=> array( 'class' => $css ),
		) );
		// De-suspend the hooks rendering.
		$this->attach_hooks();
	}
	
	// Custom css to add icon to admin bar edit button.
	function add_builder_edit_button_css() {
		?>
		<style>
		#wp-admin-bar-abc-main-menu .ab-icon:before{
			font-family: "dashicons" !important;
			content: "\f323" !important;
			font-size: 16px !important;
		}
		</style>
		<?php
	}

	/*
	 * Notification Switch
	 * Displays notification interface that will alway display
	 * even if the interface is corrupted in other places.
	 */
	function notification_switch() {
		// Suspend the hooks rendering.
		$this->detach_hooks();
		// Setup a base URL and clear it of the intial `abc-hooks` arg.
		$url = add_query_arg( 'abc-hooks', 'off' );
		?>
		<a class="abc-notification-switch" href="<?php echo esc_url( $url ); ?>">
			<span class="abc-notification-indicator"></span>
			<?php echo _e( 'Stop Showing Hooks' , 'show-hooks' ); ?>
		</a>
		<?php
		// De-suspend the hooks rendering.
		$this->attach_hooks();
	}
	
	function plugin_init() {
		if (
				! current_user_can( 'manage_options' ) || // Restrict use to Admins only
				! $this->plugin_active() // Allow filters to deactivate.
			) {
			$this->status = 'off';
			return;
		}
		// Enqueue Scripts/Styles - in head of admin
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_script' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'enqueue_script' ) );
		// Top Admin Bar
		add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu'), 90 );
		// Top Admin Bar Styles
		add_action( 'wp_print_styles', array( $this, 'add_builder_edit_button_css' ) );
		add_action( 'admin_print_styles', array( $this, 'add_builder_edit_button_css' ) );
		if ( $this->status == 'show-action-hooks' || $this->status == 'show-filter-hooks' ) {
			//Final hook - render the nested action array
			add_action( 'admin_head', array( $this, 'render_head_hooks'), 100 ); // Back-end - Admin
			add_action( 'wp_head', array( $this, 'render_head_hooks'), 100 ); // Front-end
			add_action( 'login_head', array( $this, 'render_head_hooks'), 100 ); // Login
			add_action( 'customize_controls_print_scripts', array( $this, 'render_head_hooks'), 100 ); // Customizer
		}
	}
	
	/**
	 * Enqueue Scripts
	 */
	
	public function enqueue_script($screen) {
		// Main Styles
		wp_register_style( 'abc-main-css', plugins_url( basename( plugin_dir_path( __FILE__ ) ) . '/assets/css/abc-main.css', basename( __FILE__ ) ), '', '1.1.0', 'screen' );
		wp_enqueue_style( 'abc-main-css' );
		// Main Scripts
		wp_register_script( 'abc-main-js', plugins_url( basename( plugin_dir_path( __FILE__ ) ) . '/assets/js/abc-main.js', basename( __FILE__ ) ), array('jquery'), '1.1.0' );
		wp_enqueue_script( 'abc-main-js' );
		wp_localize_script('abc-main-js', 'abc_main_js', array(
			'home_url' => get_home_url(),
			'admin_url' => admin_url(),
			'ajaxurl' => admin_url('admin-ajax.php')
		));
	}
	
	/**
	 * Localization
	 */
	public function load_translation() {
		load_plugin_textdomain( 'show-hooks', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	
	/**
	 * Render Head Hooks
	 */
	function render_head_hooks() {
		// Render all the hooks so far
		$this->render_hooks();
		// Add header marker to hooks collection
		// $this->all_hooks[] = array( 'End Header. Start Body', false, 'marker' );
		// Change to doing 'write' which will write the hook as it happens
		$this->doing = 'write';
	}
	
	/**
	 * Render all hooks already in the collection
	 */
	function render_hooks() {
		foreach ( $this->all_hooks as $nested_value ) {
			if ( 'action' == $nested_value['type'] ) {
				$this->render_action( $nested_value );
			}
		}
	}
	
	/**
	 * Hook all hooks
	 */
	
	public function hook_all_hooks( $hook ) {
		global $wp_actions, $wp_filter;
		if ( ! in_array( $hook, $this->recent_hooks ) ) {
			if ( isset( $wp_actions[$hook] ) ) {
				// Action
				$this->all_hooks[] = array(
					'ID'       => $hook,
					'callback' => false,
					'type'     => 'action',
				);
			}
			else {
				// Filter
				$this->all_hooks[] = array(
					'ID'       => $hook,
					'callback' => false,
					'type'     => 'filter',
				);
			}
		}
		if ( isset( $wp_actions[$hook] ) && !in_array( $hook, $this->recent_hooks ) && !in_array( $hook, $this->ignore_hooks ) ) {
			if ( 'write' == $this->doing ) {
				$this->render_action( end( $this->all_hooks ) );
			}
		}
		else{
			// s('(skiped-hook!)');
			// $this->render_action( $hook );
		}
		// Discarded functionality: if the hook was
		// run recently then don't show it again.
		// Better to use the once run or always run theory.
		$this->recent_hooks[] = $hook;
		if ( count( $this->recent_hooks ) > 100 ) {
			array_shift( $this->recent_hooks );
		}
	}
	
	/**
	 *
	 * Render action
	 */
	function render_action( $args = array() ) {
		global $wp_filter;
		// Get all the nested hooks
		$nested_hooks = ( isset( $wp_filter[ $args['ID'] ] ) ) ? $wp_filter[ $args['ID'] ] : false ;
		// Count the number of functions on this hook
		$nested_hooks_count = 0;
		if ( $nested_hooks ) {
			foreach ($nested_hooks as $key => $value) {
				$nested_hooks_count += count($value);
			}
		}
		?>
		<span style="display:none;" class="abc-hook abc-hook-<?php echo esc_attr($args['type']) ?> <?php echo ( $nested_hooks ) ? esc_html('abc-hook-has-hooks') : '' ; ?>" >
			<?php
			if ( 'action' == $args['type'] ) {
				?>
				<span class="abc-hook-type abc-hook-type">A</span>
				<?php
			}
			else if ( 'filter' == $args['type'] ) {
				?>
				<span class="abc-hook-type abc-hook-type">F</span>
				<?php
			}
			// Main - Write the action hook name.
			//echo esc_html( $args['ID'] );
			echo esc_attr( $args['ID'] );
			// Write the count number if any function are hooked.
			if ( $nested_hooks_count ) { ?>
				<span class="abc-hook-count">
					<?php echo esc_attr($nested_hooks_count); ?>
				</span>
				<?php
			}
			// Write out list of all the function hooked to an action.
			if ( isset( $wp_filter[$args['ID']] ) ):
				$nested_hooks = $wp_filter[$args['ID']];
				if ( $nested_hooks ): ?>
					<ul class="abc-hook-dropdown">
						<li class="abc-hook-heading">
							<?php
								$type = ucwords(esc_attr($args['type']));
								$id = esc_attr($args['ID']);
								$url = "https://codex.wordpress.org/Plugin_API/{$type}_Reference/{$id}";
								echo  "<strong>{$type}:</strong> <a href='{$url}' target='_blank'>{$id}</a>";
							?>
						</li>
						<?php
						foreach ( $nested_hooks as $nested_key => $nested_value ) :
							// Show the priority number if the following hooked functions
							?>
							<li class="abc-priority">
								<span class="abc-priority-label"><strong><?php echo esc_html('Priority:'); /* _e('Priority', 'show-hooks') */ ?></strong> <?php echo esc_attr($nested_key); ?></span>
							</li>
							<?php
							foreach ( $nested_value as $nested_inner_key => $nested_inner_value ) :
								// Show all teh functions hooked to this priority of this hook
								?>
								<li>
									<?php
									if ( $nested_inner_value['function'] && is_array( $nested_inner_value['function'] ) && count( $nested_inner_value['function'] ) > 1 ):
										
										// Hooked function ( of type object->method() )
										?>
										<span class="abc-function-string">
											<?php
											$classname = false;
											if ( is_object( $nested_inner_value['function'][0] ) || is_string( $nested_inner_value['function'][0] ) ) {
												if ( is_object( $nested_inner_value['function'][0] ) ) {
													$classname = get_class($nested_inner_value['function'][0] );
												}
												if ( is_string( $nested_inner_value['function'][0] ) ) {
													$classname = $nested_inner_value['function'][0];
												}
												if ( $classname ) {
													?><?php echo esc_attr($classname); ?>&ndash;&gt;<?php
												}
											}
											?><?php echo esc_attr($nested_inner_value['function'][1]); ?>
										</span>
										<?php
									else :
										// Hooked function ( of type function() )
										?>
										<span class="abc-function-string">
											<?php echo esc_attr($nested_inner_key); ?>
										</span>
										<?php
									endif;
									?>
								</li>
								<?php
							endforeach;
						endforeach;
						?>
					</ul>
					<?php
				endif;
			endif;
			?>
		</span>
		<?php
	}

	/*
	 * Filter Hooks Panel
	 */
	function filter_hooks_panel() {
		global $wp_filter, $wp_actions;
		?>
		<div class="abc-nested-hooks-block <?php echo ( 'show-filter-hooks' == $this->status ) ? esc_html('abc-active') : '' ; ?> ">
			<?php
			foreach ( $this->all_hooks as $va_nested_value ) {
				if ( 'action' == $va_nested_value['type'] || 'filter' == $va_nested_value['type'] ) {
					$this->render_action( $va_nested_value );
				}
				else{
					?>
					<div class="abc-collection-divider">
						<?php echo esc_attr($va_nested_value['ID']); ?>
					</div>
					<?php
				}
			}
			?>
		</div>
		<?php
	}
	
	function plugin_active() {
		// Filters to deactivate our plugin - backend, frontend or sitewide.
		// add_filter( 'abc_show_hooks_active', '__return_false' );
		// add_filter( 'abc_show_hooks_backend_active', '__return_false' );
		// add_filter( 'abc_show_hooks_frontend_active', '__return_false' );
		if ( ! apply_filters( 'abc_show_hooks_active', TRUE ) ) {
			// Sitewide.
			return FALSE;
		}
		if ( is_admin() ) {
			// Backend.
			if ( ! apply_filters( 'abc_show_hooks_backend_active', TRUE ) ) return FALSE;
		}
		else {
			// Frontend.
			if ( ! apply_filters( 'abc_show_hooks_frontend_active', TRUE ) ) return FALSE;
		}
		return TRUE;
	}
}
ABC_Show_Hooks::get_instance();
