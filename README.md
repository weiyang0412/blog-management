# blog-management
Web Technology Project

Database setup "backend->config.php"

<?php

return [
    'settings' => [
        'displayErrorDetails' => true,
        'db' => [
            'host' => '127.0.0.1', // change your local host
            'dbname' => 'my_database', // table name
            'user' => 'root', // change your database user
            'pass' => 'qwe!@#123', // change your database password
        ],
    ],
];
