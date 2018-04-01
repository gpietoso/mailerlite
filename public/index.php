<?php
/**
 * Mailerlite API
 *
 * @link      https://github.com/gpietoso/mailerlite
 * @copyright Copyright (c) 2018 Giuliano Pietoso
 */

require '../vendor/autoload.php';

$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);
$app = (new MailerLite\App())->get();
$app->run();
