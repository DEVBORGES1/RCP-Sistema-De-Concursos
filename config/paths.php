<?php
/**
 * Configuração de Caminhos do Sistema
 * 
 * Este arquivo define constantes para os caminhos do sistema,
 * facilitando a manutenção e evitando erros de caminhos relativos.
 */

// Definir o diretório raiz do projeto
define('ROOT_PATH', dirname(__DIR__));

// Caminhos de diretórios
define('APP_PATH', ROOT_PATH . '/app');
define('CLASSES_PATH', APP_PATH . '/Classes');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('ASSETS_PATH', ROOT_PATH . '/assets');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('UPLOADS_PATH', STORAGE_PATH . '/uploads');
define('DATABASE_PATH', STORAGE_PATH . '/database');

// Caminhos públicos (URLs relativas)
define('BASE_URL', '');
define('ASSETS_URL', BASE_URL . '/assets');
define('CSS_URL', ASSETS_URL . '/css');
define('UPLOADS_URL', BASE_URL . '/storage/uploads');

// Autoloader simples para classes
spl_autoload_register(function ($class) {
    $file = CLASSES_PATH . '/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

