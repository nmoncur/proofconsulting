<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/* Manage content restriction shortcodes */
class WPPCP_Private_Posts_Pages{
    
    public $current_user;
    public $private_content_settings;
    
    /* intialize the settings and shortcodes */
    public function __construct(){
        global $wppcp;

        add_action('init', array($this, 'init'));           
      
        add_action( 'add_meta_boxes', array($this,'add_post_restriction_box' ));

        add_action( 'save_post', array($this,'save_post_restrictions' ));

        add_action('template_redirect', array($this, 'validate_restrictions'), 1); 
        
    }

    public function init(){
        $this->current_user = get_current_user_id(); 
    }
    
    public function add_post_restriction_box(){
        $post_types = get_post_types( '', 'names' ); 
        $skipped_types = array('attachment','revision','nav_menu_item');

        foreach ( $post_types as $post_type ) {
            if(!in_array($post_type, $skipped_types)){
                add_meta_box(
                    'wppcp-post-restrictions',
                    __( 'WP Private Content Plus - Restriction Settings', 'wppcp' ),
                    array($this,'add_post_restrictions'),
                    $post_type

                );
            }
        }
    }

    public function add_post_restrictions($post){
        global $wppcp,$post_page_restriction_params;

        $wppcp->settings->load_wppcp_select2_scripts_style();

        $post_page_restriction_params['post'] = $post;

        ob_start();
        $wppcp->template_loader->get_template_part('post-page-restriction-meta');    
        $display = ob_get_clean();  
        echo $display;
        
        

    }

    public function save_post_restrictions($post_id){

        $skipped_types = array('attachment','revision','nav_menu_item');

        if ( ! isset( $_POST['wppcp_restriction_settings_nonce'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['wppcp_restriction_settings_nonce'], 'wppcp_restriction_settings' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! current_user_can( 'edit_posts', $post_id ) ) {
            return;
        }

        $visibility = isset( $_POST['wppcp_post_page_visibility'] ) ? $_POST['wppcp_post_page_visibility'] : 'none';
        $visible_roles = isset( $_POST['wppcp_post_page_roles'] ) ? $_POST['wppcp_post_page_roles'] : array();
        $allowed_users = isset( $_POST['wppcp_post_page_users'] ) ? $_POST['wppcp_post_page_users'] : array();

        // Update the meta field in the database.
        update_post_meta( $post_id, '_wppcp_post_page_visibility', $visibility );
        update_post_meta( $post_id, '_wppcp_post_page_roles', $visible_roles );
        update_post_meta( $post_id, '_wppcp_post_page_allowed_users', $allowed_users );
    }

    public function validate_restrictions(){
        global $wppcp,$wp_query;

        $private_content_settings  = get_option('wppcp_options');


        if(!isset($private_content_settings['general']['private_content_module_status'])){
            return;        
        }

        $this->current_user = wp_get_current_user();

        if(current_user_can('manage_options')){
            return;
        }

        if (! isset($wp_query->post->ID) ) {
            return;
        }

        if(is_page() || is_single()){
            $post_id = $wp_query->post->ID;

            $protection_status = $this->protection_status($post_id);

            if($protection_status){
                if(trim($protection_status) == 'none'){
                    if($this->global_protection_status($post_id)){
                        return;
                    }else{

                        $url = $private_content_settings['general']['post_page_redirect_url'];
                        if(trim($url) == ''){
                            $url = get_home_url();
                        }
                        wp_redirect($url);exit;
                    }
                }
                return;
            }else{
                $url = $private_content_settings['general']['post_page_redirect_url'];
                if(trim($url) == ''){
                    $url = get_home_url();
                }
                wp_redirect($url);exit;
            }

        }

        // if(is_tax() is_tag() is_category() is_author()
       
        if(is_archive() || is_feed() || is_search() ){
            
            if(isset($wp_query->posts) && is_array($wp_query->posts)){
                foreach ($wp_query->posts as $key => $post_obj) {
                    if(!$this->protection_status($post_obj->ID)){
                        $wp_query->posts[$key]->post_content = apply_filters('wppcp_archive_page_restrict_message', __('You don\'t have permission to view the content','wppcp'), array());
                    }
                }
            }
        }

        return;
    }

    public function protection_status($post_id){
        global $wppcp;

        $visibility = get_post_meta( $post_id, '_wppcp_post_page_visibility', true );

        $visible_roles = get_post_meta( $post_id, '_wppcp_post_page_roles', true );
        if(!is_array($visible_roles)){
            $visible_roles = array();
        }

        $allowed_users = get_post_meta( $post_id, '_wppcp_post_page_allowed_users', true );
        if(!is_array($allowed_users)){
            $allowed_users = array();
        }

        switch ($visibility) {
            case 'all':
                return TRUE;
                break;
            
            case 'guest':
                if(is_user_logged_in()){
                    return FALSE;
                }else{
                    return TRUE;
                }
                break;

            case 'member':
                if(is_user_logged_in()){
                    return TRUE;
                }else{
                    return FALSE;
                }
                break;

            case 'role':
                if(is_user_logged_in()){
                    if(count($visible_roles) == 0){
                        return FALSE;
                    }else{
                        $user_roles = $wppcp->roles_capability->get_user_roles_by_id($this->current_user);
                        foreach ($visible_roles as  $visible_role ) {
                            if(in_array($visible_role, $user_roles)){
                                return TRUE;
                            }
                        }
                        return FALSE;
                    }
                }else{
                    return FALSE;
                }
                
                break;
                
            case 'users':
                if(is_user_logged_in()){
                    if(count($allowed_users) == 0){
                        return FALSE;
                    }else{
                        
                        foreach ($allowed_users as  $allowed_user ) {
                            if(in_array($this->current_user->ID, $allowed_users)){
                                return TRUE;
                            }
                        }
                        return FALSE;
                    }
                }else{
                    return FALSE;
                }
                
                break;

            default:
                return "none";
                break;
        }

        return TRUE;
    }

    public function global_protection_status($post_id){
        global $wppcp;

        $private_content_settings = get_option('wppcp_options');

        $post_type = get_post_type($post_id);
        if($post_type != 'post' && $post_type != 'page'){
            return TRUE;
        }

        if($post_type == 'post'){
            $data = isset($private_content_settings['global_post_restriction']) ? $private_content_settings['global_post_restriction'] : array();
            $restrict_all_posts_status = isset($data['restrict_all_posts_status']) ? $data['restrict_all_posts_status'] :'0';
            $visibility = isset($data['all_post_visibility']) ? $data['all_post_visibility'] :'all';
            $visible_roles = isset($data['all_post_user_roles']) ? $data['all_post_user_roles'] : array();

            if($restrict_all_posts_status == '0'){
                return TRUE;
            }
         
        }else if($post_type == 'page'){
            $data = isset($private_content_settings['global_page_restriction']) ? $private_content_settings['global_page_restriction'] : array();
            $restrict_all_pages_status = isset($data['restrict_all_pages_status']) ? $data['restrict_all_pages_status'] :'0';
            $visibility = isset($data['all_page_visibility']) ? $data['all_page_visibility'] :'all';
            $visible_roles = isset($data['all_page_user_roles']) ? $data['all_page_user_roles'] : array();

            if($restrict_all_pages_status == '0'){
                return TRUE;
            }

        }else{
            return;
        }

        if(!is_array($visible_roles)){
            $visible_roles = array();
        }


        switch ($visibility) {
            case 'all':
                return TRUE;
                break;
            
            case 'guest':
                if(is_user_logged_in()){
                    return FALSE;
                }else{
                    return TRUE;
                }
                break;

            case 'member':
                if(is_user_logged_in()){
                    return TRUE;
                }else{
                    return FALSE;
                }
                break;

            case 'role':
                if(is_user_logged_in()){
                    if(count($visible_roles) == 0){
                        return FALSE;
                    }else{
                        $user_roles = $wppcp->roles_capability->get_user_roles_by_id($this->current_user);
                        foreach ($visible_roles as  $visible_role ) {
                            if(in_array($visible_role, $user_roles)){
                                return TRUE;
                            }
                        }
                        return FALSE;
                    }
                }else{
                    return FALSE;
                }
                
                break;
                
            
        }

        return TRUE;
    }
}