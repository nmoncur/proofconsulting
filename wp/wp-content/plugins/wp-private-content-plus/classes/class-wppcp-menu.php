<?php

/* Manage menu related settings */
class WPPCP_Menu{

	/* Initialize menu related acttions and filters */
	public function __construct(){
		global $wppcp;

		$this->private_content_settings  = get_option('wppcp_options');  

		if(isset($this->private_content_settings['general']['private_content_module_status'])){
           
			add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'menu_item_custom_fields' ), 10, 4 );
			add_filter( 'wp_edit_nav_menu_walker', array( $this, 'edit_nav_menu_walker' ) );
			add_action( 'wp_update_nav_menu_item', array( $this, 'update_nav_menu_item' ), 10, 3 );	
			if ( ! is_admin() ) {
				add_filter( 'wp_get_nav_menu_items', array( &$this, 'restrict_nav_menu_items' ), 10, 3 );			
			}
			add_action( 'delete_post', array( &$this, 'remove_nav_menu_meta' ), 1, 3);

		}

	}

	

	/* Include a custom menu walker class to modify the menu */
	public function edit_nav_menu_walker( $walker ) {
		require_once( dirname( __FILE__ ) . '/class-wppcp-walker-nav-menu-edit.php' );
		return 'WPPCP_Walker_Nav_Menu_Edit';
	}

	/* Display restriction settings for menu items */
	public function menu_item_custom_fields($item_id, $item, $depth, $args ) {
		global $wp_roles;

		$user_roles = apply_filters( 'nav_menu_roles', $wp_roles->role_names, $item );

		$roles = (array) get_post_meta( $item->ID, 'wppcp_nav_menu_roles', true );
		$visibility_level = get_post_meta( $item->ID, 'wppcp_nav_menu_visibility_level', true );
		if($visibility_level == ''){
			$visibility_level = '0';
		}

		?>

		<input type="hidden" name="nav-menu-role-nonce" value="<?php echo wp_create_nonce( 'nav-menu-nonce-name' ); ?>" />

		<div class="description-wide">
		    <span class="description"><?php _e( "Visibility", 'wppcp' ); ?></span>
		    <br />

		    <input type="hidden" class="nav-menu-id" value="<?php echo $item->ID ;?>" />

		    <div class="logged-input-holder" style="float: left; width: 35%;">
		        <select name='wppcp_menu_visibility_<?php echo $item->ID ;?>' id='wppcp_menu_visibility_<?php echo $item->ID ;?>' >
		        	<option value='0' <?php selected('0',$visibility_level); ?> ><?php _e('Everyone','wppcp'); ?></option>
		        	<option value='1' <?php selected('1',$visibility_level); ?> ><?php _e('Members','wppcp'); ?></option>
		        	<option value='2' <?php selected('2',$visibility_level); ?> ><?php _e('Guests','wppcp'); ?></option>
		        	<option value='3' <?php selected('3',$visibility_level); ?> ><?php _e('By User Role','wppcp'); ?></option>
		        
		        </select>
		    </div>

		    

		</div>

		<div class="description-wide" style="margin: 5px 0;">
		    <span class="description"><?php _e( "Permitted user roles", 'wppcp' ); ?></span>
		    <br />

		    <?php
		    foreach ( $user_roles as $role => $name ) {

		        $checked = checked( true, in_array( $role, $roles ) , false );
		        
		        ?>

		        <div class="" style="">
		        	<input type="checkbox" name="wppcp_menu_roles[<?php echo $item->ID ;?>][]" id="wppcp_menu_roles<?php echo $item->ID ;?>" <?php echo $checked; ?> value="<?php echo $role; ?>" />
		        	<label for="nav_menu_role-<?php echo $role; ?>-for-<?php echo $item->ID ;?>">
		        	<?php echo esc_html( $name ); ?>
		        </label>
		        </div>

		<?php } ?>

		</div>

		<?php 
	
	}

	/* Save restriction settings for menu items */
	public function update_nav_menu_item( $menu_id, $menu_item_db_id, $args ) {
		$visibility_level = get_post_meta( $menu_item_db_id, 'wppcp_nav_menu_visibility_level', true );
		$new_visibility_level = isset( $_POST['wppcp_menu_visibility_'.$menu_item_db_id] ) ? $_POST['wppcp_menu_visibility_'.$menu_item_db_id] : '0';
	
		$visibility_roles = isset($_POST['wppcp_menu_roles'][$menu_item_db_id]) ? $_POST['wppcp_menu_roles'][$menu_item_db_id] : array();

		update_post_meta( $menu_item_db_id, 'wppcp_nav_menu_visibility_level', $new_visibility_level );
		update_post_meta( $menu_item_db_id, 'wppcp_nav_menu_roles', $visibility_roles );

	}

	/* Remove restriction settings for menu items */
	public function remove_nav_menu_meta( $post_id ) {
		if( is_nav_menu_item( $post_id ) ) {
			delete_post_meta( $post_id, 'wppcp_nav_menu_visibility_level' );
			delete_post_meta( $post_id, 'wppcp_nav_menu_roles' );
		}
	}

	/* Restrict menu items based on specified conditions */
	public function restrict_nav_menu_items( $items, $menu, $args ) {

		$hide_children_of = array();

		// Iterate over the items to search and destroy
		foreach ( $items as $key => $item ) {

			$visible = true;

			$visibility_level = get_post_meta( $item->ID, 'wppcp_nav_menu_visibility_level', true );
	
			if( in_array( $item->menu_item_parent, $hide_children_of ) ){
				$visible = false;
				$hide_children_of[] = $item->ID;
			}

			if( $visible && isset( $visibility_level ) ) {

				// check all logged in, all logged out, or role
				switch( $visibility_level ) {
					case '0' :
						$visible = true;
						break;
					case '1' :
						$visible = is_user_logged_in() ? true : false;
						break;
					case '2' :
						$visible = ! is_user_logged_in() ? true : false;
						break;
					case '3' :
						$visibility_roles = (array) get_post_meta( $item->ID, 'wppcp_nav_menu_roles', true );
						$visible = false;
						foreach ( $visibility_roles as $role ) {
							if ( current_user_can( $role ) ) 
								$visible = true;
						}
						break;
				}

			}

			// add filter to work with plugins that don't use traditional roles
			$visible = apply_filters( 'nav_menu_roles_item_visibility', $visible, $item );

			// unset non-visible item
			if ( ! $visible ) {
				$hide_children_of[] = $item->ID; // store ID of item 
				unset( $items[$key] ) ;
			}

		}

		return $items;
	}
}