<?php
  return [
    'connection' => 'mysql:host=127.0.0.1',
    'dbname' => 'eventweb',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'options' => [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
  ];
?>