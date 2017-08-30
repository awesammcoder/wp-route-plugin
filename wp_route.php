<?php
    /*
    Plugin Name: WP Route
    Description: This is a routing plugin.
    Author: smmllgtp
    Version: 1.0
    */
    
    if(!class_exists('Wp_route')){
        
        class Wp_route {
            var $template_name;

            function __construct(){
                register_activation_hook(__FILE__, array(&$this, 'plugin_activate'));
                register_deactivation_hook(__FILE__, array(&$this, 'plugin_deactivate'));

                add_action( 'init', array(&$this, 'route_rewrite'));
                add_filter( 'query_vars', array(&$this, 'route_queryvars'));
                add_action( 'template_redirect', array(&$this, 'route_templates'));
            }

            function plugin_activate(){
                flush_rewrite_rules();
            }

            function plugin_deactivate(){
                flush_rewrite_rules();
            }

            function route_rewrite() {
                if($this->verify_route()){
                    add_rewrite_rule( '([^/]+)', 'index.php?route=$matches[1]', 'top');
                }
            }
             
            function route_queryvars($vars){
                $vars[] = 'route';
                return $vars;
            }

            function route_templates() {
                $routes = $this->route_config();
                if(isset($routes[get_query_var('route')])){
                    $this->template_name = $routes[get_query_var('route')];
                    $this->load_template();
                }else{
                    flush_rewrite_rules();
                }
            }

            function load_template(){
                add_filter( 'template_include', function() {
                    return $this->template_name;
                });
            }
            
            function route_config(){
                $route = apply_filters('wp_route_config', array());
                $route['test'] = plugin_dir_path(__FILE__) . 'test.php';
                return $route;
            }

            function verify_route(){
                $current_url = str_replace('http://', '', strtolower(get_site_url()));
                $current_url = str_replace('https://', '', strtolower($current_url));
                $current_uri = str_replace($current_url, '', strtolower($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
                $routes = $this->route_config();
                flush_rewrite_rules();
                if( !(get_page_by_path( substr($current_uri, 1)) OR url_to_postid(get_site_url() . $current_uri))){
                    if(!isset($routes[rtrim(substr($current_uri, 1), '/')])){
                        global $wp_query;
                        $wp_query->set_404();
                        return;
                    }
                    return true;
                }
                return false;
            }
        }

        new Wp_route();
    }
?>