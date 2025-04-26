<?php
// Définit un chemin racine absolu basé sur la position de ce fichier
define('ROOT_PATH', realpath(__DIR__ . '/../'));

// Optionnel : définir un alias pour les chemins vers certains sous-dossiers
define('CONFIG_PATH', ROOT_PATH . '/config');
define('SOURCE_PATH', ROOT_PATH . '/source');
define('PAGE_PATH', ROOT_PATH . '/page');
define('CONTROLLER_PATH', ROOT_PATH . '/controllers');

require_once ROOT_PATH . '/vendor/autoload.php';
