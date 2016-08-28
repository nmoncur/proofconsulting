<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/* Manage settings of WP Private Content Plus plugin */
class WPPCP_Settings{
    
    public $template_locations;
    public $current_user;
    
    /* Intialize actions for plugin settings */
    public function __construct(){
        
        add_action('init', array($this, 'init'));
        add_action('admin_menu', array(&$this, 'admin_settings_menu'), 9);
        add_action('init', array($this,'save_settings_page') );
        
        add_action('wp_ajax_wppcp_load_private_page_users', array($this, 'wppcp_load_private_page_users'));
        add_action('wp_ajax_wppcp_save_user_role_hierarchy', array($this, 'wppcp_save_user_role_hierarchy'));

        add_action('wp_ajax_wppcp_load_restriction_users', array($this, 'wppcp_load_restriction_users'));
        
    }

    public function init(){
        $this->current_user = get_current_user_id(); 
    }
    
    /*  Save settings tabs */
    public function save_settings_page(){

        if(!is_admin())
            return;
        
        $wppcp_settings_pages = array('wppcp-settings','wppcp-search-settings-page','wppcp-password-settings-page','wppcp-global-restrictions');
        if(isset($_POST['wppcp_tab']) && isset($_GET['page']) && in_array($_GET['page'],$wppcp_settings_pages)){
            $tab = '';
            if ( isset ( $_POST['wppcp_tab'] ) )
               $tab = $_POST['wppcp_tab']; 

            if($tab != ''){
                $func = 'save_'.$tab;
                
                if(method_exists($this,$func))
                    $this->$func();
            }
        }
    }
    
    /* Include necessary js and CSS files for admin section */
    public function include_scripts(){

        wp_register_style('wppcp_admin_css', WPPCP_PLUGIN_URL . 'css/wppcp-admin.css');
        wp_enqueue_style('wppcp_admin_css');
        
        wp_register_script('wppcp_admin_js', WPPCP_PLUGIN_URL . 'js/wppcp-admin.js', array('jquery','jquery-ui-sortable'));
        wp_enqueue_script('wppcp_admin_js');
        
        $custom_js_strings = array(        
            'AdminAjax' => admin_url('admin-ajax.php'),
            'images_path' =>  WPPCP_PLUGIN_URL . 'images/',
            'Messages'  => array(
                                'userEmpty' => __('Please select a user.','wppcp'),
                                'addToPost' => __('Add to Post','wisme'), 
                                'insertToPost' => __('Insert Files to Post','wisme'),      
                            ),
        );

        wp_localize_script('wppcp_admin_js', 'WPPCPAdmin', $custom_js_strings);
    }
    
    /* Intialize settings page and tabs */
    public function admin_settings_menu(){
        
        add_action('admin_enqueue_scripts', array($this,'include_scripts'));
        
        add_menu_page(__('Private Content Settings', 'wppcp' ), __('Private Content Settings', 'wppcp' ),'manage_options','wppcp-settings',array(&$this,'settings'));
        
        add_submenu_page('wppcp-settings',__('Global Restrictions', 'wppcp' ), __('Global Restrictions', 'wppcp' ),'manage_options','wppcp-global-restrictions',array(&$this,'global_restrictions_settings'));
        
        add_submenu_page('wppcp-settings', __('Search', 'wppcp' ), __('Search Settings', 'wppcp'),'manage_options','wppcp-search-settings-page',array(&$this,'search_settings'));
       
        add_submenu_page('wppcp-settings', __('Password', 'wppcp' ), __('Password Settings', 'wppcp'),'manage_options','wppcp-password-settings-page',array(&$this,'password_settings'));
       
        add_submenu_page('wppcp-settings', __('Private User Page', 'wppcp' ), __('Private User Page', 'wppcp'),'manage_options','wppcp-private-user-page',array(&$this,'private_user_page'));
       
        add_submenu_page('wppcp-settings', __('Mailchimp Locker', 'wppcp' ), __('Mailchimp Locker Settings (PRO)', 'wppcp'),'manage_options','wppcp-mailchimp-settings',array(&$this,'mailchimp_settings'));
       
        add_submenu_page('wppcp-settings', __('Getting Started', 'wppcp' ), __('Getting Started', 'wppcp'),'manage_options','wppcp-help',array(&$this,'help'));
       
        add_submenu_page('wppcp-settings', __('PRO Version', 'wppcp' ), __('PRO Version', 'wppcp'),'manage_options','wppcp-pro',array(&$this,'pro'));
       
    }  
    
    /* Display settings */
    public function settings(){
        global $wppcp,$wppcp_settings_data;
        
        add_settings_section( 'wppcp_section_general', __('General Settings','wppcp'), array( &$this, 'wppcp_section_general_desc' ), 'wppcp-general' );
        
        add_settings_section( 'wppcp_section_user_role_hierarchy', __('User Role Hierarchy','wppcp'), array( &$this, 'wppcp_section_general_desc' ), 'wppcp-general' );
        
        $tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'wppcp_section_general';
        $wppcp_settings_data['tab'] = $tab;
        
        $tabs = $this->plugin_options_tabs('general',$tab);
   
        $wppcp_settings_data['tabs'] = $tabs;
        
        $tab_content = $this->plugin_options_tab_content($tab);
        $wppcp_settings_data['tab_content'] = $tab_content;
        
        ob_start();
		$wppcp->template_loader->get_template_part( 'menu-page-container');
		$display = ob_get_clean();
		echo $display;
        
    
    }

    public function global_restrictions_settings(){
        global $wppcp,$wppcp_settings_data;
        
        add_settings_section( 'wppcp_section_global_post', __('Post Settings','wppcp'), array( &$this, 'wppcp_section_general_desc' ), 'wppcp-general' );
        
        add_settings_section( 'wppcp_section_global_page', __('Page Settings','wppcp'), array( &$this, 'wppcp_section_general_desc' ), 'wppcp-general' );
        
        $tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'wppcp_section_global_post';
        $wppcp_settings_data['tab'] = $tab;
        
        $tabs = $this->plugin_options_tabs('global_restrictions',$tab);
   
        $wppcp_settings_data['tabs'] = $tabs;
        
        $tab_content = $this->plugin_options_tab_content($tab);
        $wppcp_settings_data['tab_content'] = $tab_content;
        
        ob_start();
        $wppcp->template_loader->get_template_part( 'menu-page-container');
        $display = ob_get_clean();
        echo $display;
        
    
    }
    
    /* Manage settings tabs for the plugin */
    public function plugin_options_tabs($type,$tab) {
        $current_tab = $tab;
        $this->plugin_settings_tabs = array();
        
        switch($type){

            case 'general':
                $this->plugin_settings_tabs['wppcp_section_general']  = __('General Settings','wppcp');
                $this->plugin_settings_tabs['wppcp_section_user_role_hierarchy']  = __('User Role Hierarchy','wppcp');
                break;

            case 'global_restrictions':
                $this->plugin_settings_tabs['wppcp_section_global_post']  = __('Post Settings','wppcp');
                $this->plugin_settings_tabs['wppcp_section_global_page']  = __('Page Settings','wppcp');
                break;   

            case 'search':
                $this->plugin_settings_tabs['wppcp_section_search_general']  = __('Search Settings','wppcp');
                $this->plugin_settings_tabs['wppcp_section_search_restrictions']  = __('Search Restrictions','wppcp');
                break;

            case 'password':
                $this->plugin_settings_tabs['wppcp_section_password_global']  = __('Password Settings','wppcp');                
                break;          

        }
        
        ob_start();
        ?>

        <h2 class="nav-tab-wrapper">
        <?php 
            foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
            $active = $current_tab == $tab_key ? 'nav-tab-active' : '';
            $page = isset($_GET['page']) ? $_GET['page'] : '';
        ?>
                <a class="nav-tab <?php echo $active; ?> " href="?page=<?php echo $page; ?>&tab=<?php echo $tab_key; ?>"><?php echo $tab_caption; ?></a>
            
        <?php } ?>
        </h2>

        <?php
                
        return ob_get_clean();
    }
    
    /* Manage settings tab contents for the plugin */
    public function plugin_options_tab_content($tab,$params = array()){
        global $wppcp,$wppcp_settings_data,$wppcp_search_settings_data,$wppcp_password_settings_data;
        
        $post_types = $wppcp->posts->get_post_types();

        $private_content_settings = get_option('wppcp_options');

        $this->load_wppcp_select2_scripts_style();
        
        ob_start();
        switch($tab){
            case 'wppcp_section_general':                
	            $data = isset($private_content_settings['general']) ? $private_content_settings['general'] : array();
      
                $wppcp_settings_data['tab'] = $tab;
                $wppcp_settings_data['private_content_module_status'] = isset($data['private_content_module_status']) ? $data['private_content_module_status'] :'0';
                $wppcp_settings_data['private_content_tab_status'] = isset($data['private_content_tab_status']) ? $data['private_content_tab_status'] :'0';
                $wppcp_settings_data['post_page_redirect_url'] = isset($data['post_page_redirect_url']) ? $data['post_page_redirect_url'] :'';
                $wppcp_settings_data['search_restrictions_module_status'] = isset($data['search_restrictions_module_status']) ? $data['search_restrictions_module_status'] :'0';
                              
                $wppcp->template_loader->get_template_part('general-settings');            
                break;
            
            case 'wppcp_section_user_role_hierarchy':                
                $data = isset($private_content_settings['role_hierarchy']) ? $private_content_settings['role_hierarchy'] : array();
            
                $wppcp_settings_data['hierarchy'] = isset($data['hierarchy']) ? $data['hierarchy'] : array();         
                $wppcp_settings_data['tab'] = $tab;
            
                $wppcp->template_loader->get_template_part('user-role-hierarchy');            
                break;

            case 'wppcp_section_global_post':                
                $data = isset($private_content_settings['global_post_restriction']) ? $private_content_settings['global_post_restriction'] : array();

                $wppcp_settings_data['tab'] = $tab;
                $wppcp_settings_data['restrict_all_posts_status'] = isset($data['restrict_all_posts_status']) ? $data['restrict_all_posts_status'] :'0';
                $wppcp_settings_data['all_post_visibility'] = isset($data['all_post_visibility']) ? $data['all_post_visibility'] :'all';
                $wppcp_settings_data['all_post_user_roles'] = isset($data['all_post_user_roles']) ? $data['all_post_user_roles'] : array();
                             
                $wppcp->template_loader->get_template_part('global-post-restriction-settings');            
                break;

            case 'wppcp_section_global_page':                
                $data = isset($private_content_settings['global_page_restriction']) ? $private_content_settings['global_page_restriction'] : array();

                $wppcp_settings_data['tab'] = $tab;
                $wppcp_settings_data['restrict_all_pages_status'] = isset($data['restrict_all_pages_status']) ? $data['restrict_all_pages_status'] :'0';
                $wppcp_settings_data['all_page_visibility'] = isset($data['all_page_visibility']) ? $data['all_page_visibility'] :'all';
                $wppcp_settings_data['all_page_user_roles'] = isset($data['all_page_user_roles']) ? $data['all_page_user_roles'] : array();
                             
                $wppcp->template_loader->get_template_part('global-page-restriction-settings');            
                break;

            // Settings for Search
            case 'wppcp_section_search_general':                
                $data = isset($private_content_settings['search_general']) ? $private_content_settings['search_general'] : array();

                $wppcp_search_settings_data['tab'] = $tab;
                $wppcp_search_settings_data['blocked_post_search'] = isset($data['blocked_post_search']) ? (array) $data['blocked_post_search'] : array();
                $wppcp_search_settings_data['blocked_page_search'] = isset($data['blocked_page_search']) ? (array) $data['blocked_page_search'] : array();
                $wppcp_search_settings_data['post_types'] = $post_types;

                $wppcp_search_settings_data = apply_filters('wppcp_search_setting_data',$wppcp_search_settings_data, array('data' => $data, 'section' => 'wppcp_section_search_general' ) );
             
                
            //echo "<pre>";print_r($wppcp->posts->get_post_types());exit;    

                $wppcp->template_loader->get_template_part('search-general-settings');            
                break;

            case 'wppcp_section_search_restrictions':                
                $data = isset($private_content_settings['search_restrictions']) ? $private_content_settings['search_restrictions'] : array();
     // echo "<pre>";print_r($data);exit;
                $wppcp_search_settings_data['tab'] = $tab;
                $wppcp_search_settings_data['everyone_search_types'] = isset($data['everyone_search_types']) ? (array) $data['everyone_search_types'] :array();
                $wppcp_search_settings_data['guests_search_types'] = isset($data['guests_search_types']) ? (array) $data['guests_search_types'] :array();
                $wppcp_search_settings_data['members_search_types'] = isset($data['members_search_types']) ? (array) $data['members_search_types'] :array();
                $wppcp_search_settings_data['data'] = $data; 
                $wppcp_search_settings_data['post_types'] = $post_types;

                $wppcp->template_loader->get_template_part('search-restrictions');            
                break;

            // Settings for Global Password
            case 'wppcp_section_password_global':                
                $data = isset($private_content_settings['password_global']) ? $private_content_settings['password_global'] : array();

                $wppcp_password_settings_data['tab'] = $tab;
                $wppcp_password_settings_data['global_password_protect'] = isset($data['global_password_protect']) ? $data['global_password_protect'] : 'disabled';
                $wppcp_password_settings_data['global_protect_password'] = isset($data['global_protect_password']) ? $data['global_protect_password'] : '';
                $wppcp_password_settings_data['password_form_title'] = isset($data['password_form_title']) ? $data['password_form_title'] : __('Protected Content','wppcp');
                $wppcp_password_settings_data['password_form_message'] = isset($data['password_form_message']) ? $data['password_form_message'] : __('This content is password protected. Please enter the password to view the content.','wppcp');
                

                $wppcp_password_settings_data = apply_filters('wppcp_password_setting_data',$wppcp_password_settings_data, array('data' => $data, 'section' => 'wppcp_section_password_global' ) );
                $wppcp->template_loader->get_template_part('password-global-settings');            
                break;
        }
        
        $display = ob_get_clean();
        return $display;
        
    }

    /* Save general settings */
    public function save_wppcp_section_general(){
        global $wppcp;

        if(isset($_POST['wppcp_general'])){
            foreach($_POST['wppcp_general'] as $k=>$v){
                $this->settings[$k] = $v;
            }            
        }
        
        $wppcp_options = get_option('wppcp_options');
        $wppcp_options['general'] = $this->settings;
        update_option('wppcp_options',$wppcp_options);
        add_action( 'admin_notices', array( $this, 'admin_notices' ) );  

        
    }

    public function save_wppcp_section_global_post(){
        global $wppcp;

        if(isset($_POST['wppcp_global_post_restriction'])){
            foreach($_POST['wppcp_global_post_restriction'] as $k=>$v){
                $this->settings[$k] = $v;
            }      
               
        }
        
        $wppcp_options = get_option('wppcp_options');
        $wppcp_options['global_post_restriction'] = $this->settings;

        update_option('wppcp_options',$wppcp_options);
        add_action( 'admin_notices', array( $this, 'admin_notices' ) ); 
    }

    public function save_wppcp_section_global_page(){
        global $wppcp;

        if(isset($_POST['wppcp_global_page_restriction'])){
            foreach($_POST['wppcp_global_page_restriction'] as $k=>$v){
                $this->settings[$k] = $v;
            }      
               
        }
        
        $wppcp_options = get_option('wppcp_options');
        $wppcp_options['global_page_restriction'] = $this->settings;
        update_option('wppcp_options',$wppcp_options);
        add_action( 'admin_notices', array( $this, 'admin_notices' ) ); 
    }
    
    
    /* Display private user page add content form */
    public function private_user_page(){
        global $wppcp,$wppcp_private_page_params,$wpdb;
        
        $wppcp_private_page_params = array();
        
        $this->load_wppcp_select2_scripts_style();
        
        $private_page_user = 0;
        if($_POST && isset($_POST['wppcp_private_page_user_load']) && current_user_can('manage_options') ){
            $private_page_user = isset($_POST['wppcp_private_page_user']) ? $_POST['wppcp_private_page_user'] : 0;
            $user = get_user_by( 'id', $private_page_user );
            $wppcp_private_page_params['display_name'] = $user->data->display_name;
            $wppcp_private_page_params['user_id'] = $private_page_user;
        }
        

        
        if($_POST && isset($_POST['wppcp_private_page_content_submit']) && current_user_can('manage_options') ){
            if (isset( $_POST['wppcp_private_page_nonce_field'] ) && wp_verify_nonce( $_POST['wppcp_private_page_nonce_field'], 'wppcp_private_page_nonce' ) ) {
                $user_id = isset($_POST['wppcp_user_id']) ? $_POST['wppcp_user_id'] : 0; 
                $private_content = isset($_POST['wppcp_private_page_content']) ? $_POST['wppcp_private_page_content'] : '';
                $updated_date = date("Y-m-d H:i:s");
                
                $sql  = $wpdb->prepare( "SELECT content FROM " . $wpdb->prefix . WPPCP_PRIVATE_CONTENT_TABLE . " WHERE user_id = %d ", $user_id );
                $result = $wpdb->get_results($sql);
                if($result){
                    $sql  = $wpdb->prepare( "Update " . $wpdb->prefix . WPPCP_PRIVATE_CONTENT_TABLE ." set content=%s,updated_at=%s where user_id=%d ", $private_content,$updated_date, $user_id );
                }else{
                    $sql  = $wpdb->prepare( "Insert into " . $wpdb->prefix . WPPCP_PRIVATE_CONTENT_TABLE ."(user_id,content,type,updated_at) values(%d,%s,%s,%s)", $user_id, $private_content, 'ADMIN', $updated_date );
                }
                
                
                if($wpdb->query($sql) === FALSE){
                    $wppcp_private_page_params['message'] = __('Private content update failed.','wppcp');
                    $wppcp_private_page_params['message_status'] = FALSE;
                }else{
                    $wppcp_private_page_params['message'] = __('Private content updated successfully.','wppcp');
                    $wppcp_private_page_params['message_status'] = TRUE;
                }        
            }
        }
        
        $sql  = $wpdb->prepare( "SELECT content FROM " . $wpdb->prefix . WPPCP_PRIVATE_CONTENT_TABLE . " WHERE user_id = %d ", $private_page_user );
        $result = $wpdb->get_results($sql);
        if($result){
            $wppcp_private_page_params['private_content'] = stripslashes($result[0]->content);
        }else{
            $wppcp_private_page_params['private_content'] = '';
        }
        
        
        
        
        ob_start();
        $wppcp->template_loader->get_template_part('private-user-page');
        $display = ob_get_clean();        
        echo $display;
    }
    
    /* Load Select 2 library for settings section */
    public function load_wppcp_select2_scripts_style(){          

        wp_register_script('wppcp_select2_js', WPPCP_PLUGIN_URL . 'js/select2/wppcp-select2.min.js');
        wp_enqueue_script('wppcp_select2_js');
        
        wp_register_style('wppcp_select2_css', WPPCP_PLUGIN_URL . 'js/select2/wppcp-select2.min.css');
        wp_enqueue_style('wppcp_select2_css');

    }
    
    /* Get the users for the private page content form */
    public function wppcp_load_private_page_users(){
        global $wpdb;
        
        $search_text  = isset($_POST['q']) ? $_POST['q'] : '';
        
        $args = array('number' => 20);
        if($search_text != ''){
            $args['search'] = "*".$search_text."*";
        }
        
        $user_results = array();
        $user_json_results = array();
        
        $user_query = new WP_User_Query( $args );
        $user_results = $user_query->get_results();

        foreach($user_results as $user){
            if($user->ID != $this->current_user){
                array_push($user_json_results , array('id' => $user->ID, 'name' => $user->data->display_name) ) ;
            }
                       
        }
        
        echo json_encode(array('items' => $user_json_results ));exit;
    }  
    
    
    /* Save user role hierarchy of the site */
    public function wppcp_save_user_role_hierarchy(){
        global $wppcp,$user_role_hierarchy_result;
        if(is_user_logged_in() && current_user_can('manage_options')){
            $private_content_settings = get_option('wppcp_options');

            $user_role_hierarchy = isset($_POST['user_role_hierarchy']) ? $_POST['user_role_hierarchy'] : array();
            
            $private_content_settings['role_hierarchy']['hierarchy'] = $user_role_hierarchy;
            
            update_option('wppcp_options',$private_content_settings);
            
            $result = array('status' => 'success', 'msg' => __('Role Hierarchy saved succefully.','wppcp'));
            
        }else{
            $result = array('status' => 'error', 'msg' => __('Role Hierarchy save failed.','wppcp'));
        }
        
        echo json_encode($result);exit;
    }
    
    /* Display settings saved message */  
    public function admin_notices(){
        ?>
        <div class="updated">
          <p><?php esc_html_e( 'Settings saved successfully.', 'wppcp' ); ?></p>
       </div>
        <?php
    }

    /* Help and information about the plugin */
    public function help(){
        global $wppcp;
        ob_start();
        $wppcp->template_loader->get_template_part('plugin-help');    
        $display = ob_get_clean();  
    
        echo $display;
    }

    public function search_settings(){

        global $wppcp,$wppcp_settings_data;
        
        add_settings_section( 'wppcp_section_search_general', __('Search Settings','wppcp'), array( &$this, 'wppcp_section_general_desc' ), 'wppcp-search-general' );
        
        add_settings_section( 'wppcp_section_search_restrictions', __('Search Restrictions','wppcp'), array( &$this, 'wppcp_section_general_desc' ), 'wppcp-search-general' );
        
        $tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'wppcp_section_search_general';
        $wppcp_settings_data['tab'] = $tab;
        
        $tabs = $this->plugin_options_tabs('search',$tab);
   
        $wppcp_settings_data['tabs'] = $tabs;
        
        $tab_content = $this->plugin_options_tab_content($tab);
        $wppcp_settings_data['tab_content'] = $tab_content;
        
        ob_start();
        $wppcp->template_loader->get_template_part( 'menu-page-container');
        $display = ob_get_clean();
        echo $display;

    }

    public function save_wppcp_section_search_general(){
        global $wppcp;

        if(isset($_POST['wppcp_search_general'])){
            foreach($_POST['wppcp_search_general'] as $k=>$v){
                $this->settings[$k] = $v;
            }      
               
        }
        
        $wppcp_options = get_option('wppcp_options');
        $wppcp_options['search_general'] = $this->settings;
        update_option('wppcp_options',$wppcp_options);
        add_action( 'admin_notices', array( $this, 'admin_notices' ) ); 
    }

    public function save_wppcp_section_search_restrictions(){
        global $wppcp;

        if(isset($_POST['wppcp_search_restrictions'])){
            foreach($_POST['wppcp_search_restrictions'] as $k=>$v){
                $this->settings[$k] = $v;
            } 

            $wppcp_options = get_option('wppcp_options');
            $wppcp_options['search_restrictions'] = $this->settings;
            update_option('wppcp_options',$wppcp_options);
            add_action( 'admin_notices', array( $this, 'admin_notices' ) );           
        }
        
         
    }

    public function password_settings(){

        global $wppcp,$wppcp_settings_data;
        
        add_settings_section( 'wppcp_section_password_global', __('Global Password Settings','wppcp'), array( &$this, 'wppcp_section_general_desc' ), 'wppcp-password-global' );
        
        //add_settings_section( 'wppcp_section_search_restrictions', __('Search Restrictions','wppcp'), array( &$this, 'wppcp_section_general_desc' ), 'wppcp-search-general' );
        
        $tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'wppcp_section_password_global';
        $wppcp_settings_data['tab'] = $tab;
        
        $tabs = $this->plugin_options_tabs('password',$tab);
   
        $wppcp_settings_data['tabs'] = $tabs;
        
        $tab_content = $this->plugin_options_tab_content($tab);
        $wppcp_settings_data['tab_content'] = $tab_content;
        
        ob_start();
        $wppcp->template_loader->get_template_part( 'menu-page-container');
        $display = ob_get_clean();
        echo $display;

    }

    public function save_wppcp_section_password_global(){
        global $wppcp;

        if(isset($_POST['wppcp_password_global'])){
            foreach($_POST['wppcp_password_global'] as $k=>$v){
                $this->settings[$k] = $v;
            }      
               
        }
        
        $wppcp_options = get_option('wppcp_options');
        $wppcp_options['password_global'] = $this->settings;
        update_option('wppcp_options',$wppcp_options);
        add_action( 'admin_notices', array( $this, 'admin_notices' ) ); 
    }

    /* Get the users for restrictions on various locations */
    public function wppcp_load_restriction_users(){
        global $wpdb;
        
        $search_text  = isset($_POST['q']) ? $_POST['q'] : '';
        
        $args = array('number' => 20);
        if($search_text != ''){
            $args['search'] = "*".$search_text."*";
        }
        
        $user_results = array();
        $user_json_results = array();
        
        $user_query = new WP_User_Query( $args );
        $user_results = $user_query->get_results();

        foreach($user_results as $user){
            if($user->ID != $this->current_user){
                array_push($user_json_results , array('id' => $user->ID, 'name' => $user->data->display_name) ) ;
            }
                       
        }
        
        echo json_encode(array('items' => $user_json_results ));exit;
    } 


    public function pro(){
        global $wppcp;
        ob_start();
        $wppcp->template_loader->get_template_part('plugin-pro');    
        $display = ob_get_clean();  
    
        echo $display;
    }

    public function mailchimp_settings(){
        global $wppcp,$wppcp_settings_data;
        
       
        ob_start();
        $wppcp->template_loader->get_template_part( 'mailchimp-general-settings');
        $display = ob_get_clean();
        echo $display;
    }

}




// add_action( 'admin_footer', 'urgfa_dequeue_woocommerce_wppcp_select2' );

// function urgfa_dequeue_woocommerce_wppcp_select2() {
 
//     global $pagenow;

//     if ( class_exists( 'WooCommerce' ) ) {
    
//         if(wp_script_is( 'wppcp_wppcp_select2_js' , $list = 'enqueued' )){
//             if(wp_script_is( 'wppcp_select2' , $list = 'enqueued' )){
//                 echo "fwfwr";
//             }
//             wp_dequeue_style( 'wppcp_select2' );
      

//             wp_dequeue_script( 'wppcp_select2');
         
//             echo "kyuklil";

//         }        
//     }
     
// } 

// add_action( 'wp_enqueue_scripts', 'mgt_dequeue_stylesandscripts', 100 );

// function mgt_dequeue_stylesandscripts() {
//     if ( class_exists( 'woocommerce' ) ) {
//         wp_dequeue_style( 'wppcp_select2' );
//         wp_deregister_style( 'wppcp_select2' );

//         wp_dequeue_script( 'wppcp_select2');
//         wp_deregister_script('wppcp_select2');

//     } 
// } 

