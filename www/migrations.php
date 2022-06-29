<?php
declare(strict_types=1);

use app\core\Application;
use Dotenv\Dotenv;
use app\constants\Migration;

require_once sprintf('%s/vendor/autoload.php', __DIR__);
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = [
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ]
];

$app = new Application(__DIR__, $config);

$shortOpts = sprintf('%s%s%s%s:',
    Migration::SHORT_OPTION_HELP,
    Migration::SHORT_OPTION_UP,
    Migration::SHORT_OPTION_DOWN,
    Migration::SHORT_OPTION_NUMBER
);
$longOpts = [
    Migration::LONG_OPTION_HELP,
    Migration::LONG_OPTION_UP,
    Migration::LONG_OPTION_DOWN,
    sprintf('%s:', Migration::LONG_OPTION_NUMBER),
];

$options = getopt($shortOpts, $longOpts);
$optionKeys = array_keys($options);

$action = null;
$migrationNumber = null;
switch (true) {
    case (in_array(Migration::SHORT_OPTION_HELP, $optionKeys) || in_array(Migration::LONG_OPTION_HELP, $optionKeys)):
        echo sprintf('Чтобы применить все новые миграции, нужно выполнить команду 
        "php migrations.php" или "php migrations.php -u" или "php migrations.php --up"%s', PHP_EOL);
        echo sprintf('Чтобы применить какую-то одну миграцию, нужно выполнить команду
        "php migrations.php -u -n нужныйНомер" или "php migrations.php --up --number нужныйНомер"%s', PHP_EOL);
        echo sprintf('Чтобы откатить все имеющиеся миграции, нужно выполнить команду 
        "php migrations.php -d" или "php migrations.php --down"%s', PHP_EOL);
        echo sprintf('Чтобы откатить какую-то одну миграцию, нужно выполнить команду
        "php migrations.php -d -n нужныйНомер" или "php migrations.php --down --number нужныйНомер"%s', PHP_EOL);
        echo sprintf('Номер миграции должен начинаться с буквы "m". 
        Можно указывать как только номер миграции, так и полное название%s', PHP_EOL);
        exit;
    case (in_array(Migration::SHORT_OPTION_UP, $optionKeys) || in_array(Migration::LONG_OPTION_UP, $optionKeys)):
        $action = Migration::LONG_OPTION_UP;
        break;
    case (in_array(Migration::SHORT_OPTION_DOWN, $optionKeys) || in_array(Migration::LONG_OPTION_DOWN, $optionKeys)):
        $action = Migration::LONG_OPTION_DOWN;
        break;
}

if (is_null($action)) {
    $action = Migration::LONG_OPTION_UP;
}
if (!empty($options[Migration::SHORT_OPTION_NUMBER])) {
    $migrationNumber = $options[Migration::SHORT_OPTION_NUMBER];
} elseif (!empty($options[Migration::LONG_OPTION_NUMBER])) {
    $migrationNumber = $options[Migration::LONG_OPTION_NUMBER];
}

$app->db->applyMigrations($action, $migrationNumber);
