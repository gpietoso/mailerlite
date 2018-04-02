# Mailerlite Subscriber & Field API

## Requirements

- PHP 7.0
- MySQL 5.x
- Localhost Mysql server with created DB named: "testing_db"

## Installation

- Run composer update
- Run vendor/bin/phinx migrate -e testing
- Run vendor/bin/phinx seed:run -e testing -s FieldTypeSeeder -s FieldSeeder
- Run tests: 
- ./vendor/bin/phpunit ./test/FieldTest.php
- ./vendor/bin/phpunit ./test/SubscriberTest.php

Please note that some tests presupose created records, specially deletion, updates and gets. Please refer to the source code inside the test files.
