<?php
/**
 * Mailerlite API
 *
 * @link      https://github.com/gpietoso/mailerlite
 * @copyright Copyright (c) 2018 Giuliano Pietoso
 */

namespace MailerLite;

use PDO;

/**
 * Base Model
 *
 * This class provides common connection for all models
 */
class BaseModel
{
    var $_connection;

    public function __construct()
    {

        $dbhost = "localhost";
        $dbuser = "root";
        $dbpass = "";
        $dbname = "testing_db";

        $this->_connection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
        $this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}
