<?php
// Función simple de autoload
function autoloadModels($className) {
    $file = 'models/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

spl_autoload_register('autoloadModels');
?>