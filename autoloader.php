<?php
    define("BASE_PATH", __DIR__);
    define("BASE_URL", "/newPSG");
	// echo "base:".BASE_PATH." ";
	spl_autoload_register(function ($class) {
		$prefix = 'App\\';
		$base_dir = BASE_PATH . '/src/';

		$len = strlen($prefix);
		if (strncmp($prefix, $class, $len) !== 0) {
			return;
		}

		$relative_class = substr($class, $len);
		$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

		// error_log("Autoloader: Buscando la clase $class en $file ");

		if (file_exists($file)) {
			require $file;
		} else {
			error_log("Autoloader: No se encontro el archivo para la clase $class en $file ");
		}
	});
