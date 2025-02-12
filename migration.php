<?php
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Migration.php';

use Core\Migration;

if (php_sapi_name() === 'cli') {
    $command = $argv[1] ?? null;
    switch ($command) {
        case 'migrate':
            Migration::migrate();
            break;
        case 'reset':
            Migration::reset();
            break;
        case 'next':
            Migration::next();
            break;
        case 'previous':
            Migration::previous();
            break;
        default:
            echo "Commandes disponibles :\n";
            echo "  php migration.php migrate   - Exécuter toutes les migrations\n";
            echo "  php migration.php reset     - Réinitialiser la base de données\n";
            echo "  php migration.php next      - Exécuter la prochaine migration\n";
            echo "  php migration.php previous  - Annuler la dernière migration\n";
            break;
    }
}
