<?php
spl_autoload_register(function ($class) {
	$class = str_replace('\\', '/', $class);
    include __DIR__.'/lib/' . $class . '.php';
});
?>