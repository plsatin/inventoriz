<?php
if (!function_exists('public_path')) {
   /**
    * Get the path to the public folder.
    *
    * @param  string $path
    * @return string
    */
    function public_path($path = '')
    {
        return env('PUBLIC_PATH', base_path('public')) . ($path ? '/' . $path : $path);
    }
}



/*

Alternatively if you do not wish to add a third party package simply create a file in your app directory called helpers.php and then within your composer.json file add the following within the "autoload" part and run composer dump-autoload to refresh the autoloader cache:

"files": [
    "app/helpers.php"
],
Then within helpers.php add the following content:

<?php
if (!function_exists('public_path')) {
    function public_path($path = '')
    {
        return env('PUBLIC_PATH', base_path('public')) . ($path ? '/' . $path : $path);
    }
}


*/