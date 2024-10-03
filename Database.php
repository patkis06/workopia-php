<?php

/**
 * Constructor for Database class
 * 
 * @param array $config
 */

class Database
{
  private $conn;

  public function __construct($config)
  {
    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['db_name']}";

    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
    ];

    try {
      $this->conn = new PDO($dsn, $config['username'], $config['password'], $options);
    } catch (PDOException $e) {
      throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
  }

  /**
   * Query method
   * 
   * @param string $sql
   * @return PDOStatement
   * @return PDOException
   */

  public function query($sql)
  {
    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      return $stmt;
    } catch (PDOException $e) {
      throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
  }
}
