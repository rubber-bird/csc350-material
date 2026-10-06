<?php
// Where to find the database server.
//
// These are XAMPP's defaults: MariaDB on this machine, port 3306, user root
// with no password. Change them only if you changed XAMPP.
//
// The runner DROPS and re-creates the database named below before every
// run, so never keep anything of your own in it.
return [
    'host'     => getenv('SQL_HOST')     ?: '127.0.0.1',
    'port'     => (int) (getenv('SQL_PORT') ?: 3306),
    'user'     => getenv('SQL_USER')     ?: 'root',
    'password' => getenv('SQL_PASSWORD') ?: '',
    'database' => getenv('SQL_DATABASE') ?: 'sql_fundamentals',
];
