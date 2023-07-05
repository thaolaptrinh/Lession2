<?php

namespace App\Core;

use PDO;

class DB
{
  private static $conn;

  public static function connect()
  {
    if (!self::$conn) {
      try {
        $dsn = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'] . ';port=' . $_ENV['DB_PORT'];
        self::$conn = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
        self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$conn->exec("set names 'utf8'");
      } catch (\Exception $e) {
        echo $e->getMessage();
      }
    }
  }

  public static function query($sql)
  {
    try {
      self::connect();
      $stmt = self::$conn->prepare($sql);
      $stmt->execute();
      return $stmt;
    } catch (\Exception $e) {
      echo $e->getMessage();
    }
  }

  public static function getRowByWhere($table, $where)
  {
    try {
      self::connect();
      $sql = 'SELECT * FROM ' . $table . ' WHERE ' . $where;
      $result = self::$conn->query($sql);

      if (!$result) return false;
      $data = $result->fetch(PDO::FETCH_ASSOC);
      return $data;
    } catch (\Exception $e) {
      echo $e->getMessage();
      return false;
    }
  }

  public static function insert($table, $data)
  {
    try {
      self::connect();
      $fields = array_keys($data);
      $values = array_values($data);
      $placeholders = implode(',', array_fill(0, count($values), '?'));

      $sql = 'INSERT INTO ' . $table . ' (' . implode(',', $fields) . ') VALUES (' . $placeholders . ')';
      $stmt = self::$conn->prepare($sql);
      $stmt->execute($values);
      return $stmt->rowCount() > 0;
    } catch (\Exception $e) {
      echo $e->getMessage();
    }
  }

  public static function update($table, $data, $where)
  {
    try {
      self::connect();
      $sql = '';
      foreach ($data as $key => $value) {
        $sql .= "{$key} = " . self::$conn->quote($value) . ",";
      }
      $sql = 'UPDATE ' . $table . ' SET ' . trim($sql, ',') . ' WHERE ' . $where;
      $result =  self::$conn->query($sql);
      if (!$result)  return false;
      $updatedData = self::getRowByWhere($table, $where);
      return $updatedData;
    } catch (\Exception $e) {
      echo $e->getMessage();
    }
  }

  public static function remove($table, $where)
  {
    try {
      self::connect();
      $sql = "DELETE FROM $table WHERE $where";
      $stmt = self::$conn->prepare($sql);
      $stmt->execute();
      return $stmt->rowCount() > 0;
    } catch (\Exception $e) {
      echo $e->getMessage();
    }
  }

  public static function get_list($sql)
  {
    try {
      self::connect();
      $stmt = self::$conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $result;
    } catch (\Exception $e) {
      echo $e->getMessage();
    }
  }

  public static function get_row($sql)
  {
    try {
      self::connect();
      $stmt = self::$conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result ?: [];
    } catch (\Exception $e) {
      echo $e->getMessage();
    }
  }

  public static function num_rows($sql)
  {
    try {
      self::connect();
      $stmt = self::$conn->query($sql);
      $row = $stmt->rowCount();

      return $row ?: 0;
    } catch (\Exception $e) {
      echo $e->getMessage();
    }
  }
}
