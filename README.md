# WP Route Plugin
> A wordpress plugin that integrates routing functionality in your wordpress project.

## Installation
> Copy the plugin folder in your plugins directory inside wp-content folder in your wordpress project.
* Import the plugin folder to your wordpress.
* Don't forget to activate the plugin.

## Documentation
> To add routes in your wordpress, use the filter hook '<b><i>wp_route_config</i></b>' below.
```php

    function setup_routes($routes){
        // $routes[{endpoint}] = {template/file/path.php};
        $routes['home'] = 'tempalates.php';

    }

    add_filter('wp_route_config', 'setup_routes');
```