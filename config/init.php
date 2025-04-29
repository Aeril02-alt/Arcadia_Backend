<?php
// Définit un chemin racine absolu basé sur la position de ce fichier
define('ROOT_PATH', realpath(__DIR__ . '/../'));

// Optionnel : définir un alias pour les chemins vers certains sous-dossiers
define('CONFIG_PATH', ROOT_PATH . '/config');
define('SOURCE_PATH', ROOT_PATH . '/source');
define('PAGE_PATH', ROOT_PATH . '/page'); /* changement , mise en place des page web dans la racine du projet */
define('API_PATH', ROOT_PATH . '/page/api');
define('CONTROLLER_PATH', ROOT_PATH . '/controllers');
define('DOC_PATH', ROOT_PATH . '/doc');

require_once ROOT_PATH . '/vendor/autoload.php';
