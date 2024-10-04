<?php
    define("BASE_PATH", __DIR__);
    spl_autoload_register(function ($class) {
        $prefix = 'App\\';
        $base_dir = BASE_PATH . '/src/';
        
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }
        
        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        if (file_exists($file)) {
            require $file;
        } else {
            error_log("Autoloader: No se encontró el archivo para la clase $class en $file");
        }
    });
