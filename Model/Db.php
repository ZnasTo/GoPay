<?php
// Wrapper pro snadnější práci s databází s použitím PDO a automatickým
// zabezpečením parametrů (proměnných) v dotazech.
class Db {

	// Databázové spojení
  private static $connection;

	// Výchozí nastavení ovladače
  private static $settings = array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_EMULATE_PREPARES => false,
	);

	// Připojí se k databázi pomocí daných údajů
  public static function connect($server, $user, $password, $database)
  {
	  if (!isset(self::$connection)) 
    {
        $dsn = "mysql:host=$server;dbname=$database;charset=utf8";
		self::$connection = new PDO(
			$dsn,
			$user,
			$password,
			self::$settings
		);
	  }
	}
	
	// Spustí dotaz a vrátí z něj první řádek
  public static function queryOne($query, $parameters = array()) {
		$result = self::$connection->prepare($query);
		$result->execute($parameters);
	  	return $result->fetch();
	}

	// Spustí dotaz a vrátí všechny jeho řádky jako pole asociativních polí
  public static function queryAll($query, $parameters = array()) {
		$result = self::$connection->prepare($query);
		$result->execute($parameters);
		return $result->fetchAll();
	}
	
	// Spustí dotaz a vrátí z něj první sloupec prvního řádku
  public static function queryAlone($query, $parameters = array()) {
		$result = self::queryOne($query, $parameters);
		return $result[0];
		
	}
	
	// Spustí dotaz a vrátí počet ovlivněných řádků
	public static function query($query, $parameters = array()) {
		$result = self::$connection->prepare($query);
		$result->execute($parameters);
		return $result->rowCount();
	}
	
	
	// Vloží do tabulky nový řádek jako data z asociativního pole
	public static function insert($table, $parameters = array()) {  
		return self::query("
		INSERT INTO $table 
		(". implode(', ', array_keys($parameters)). ") 
		VALUES
		(". str_repeat('?,', sizeOf($parameters)-1). "?)
		",
				array_values($parameters));
	}
	// Spustí dotaz a vrátí id nově vytvořeného záznamu, jinak vrátí false
	public static function queryAndReturnId($query, $parameters = array()) {
		//nejedna se on vkladaci dotaz
		if(!str_contains(strtoupper($query),"INSERT")) {
			return false;
		}

		$query = self::query($query,$parameters);
		
		if($query) {
			return self::$connection->lastInsertId();
		} else {
			return $query;
		}
		
	}
	
	// Změní řádek v tabulce tak, aby obsahoval data z asociativního pole
	public static function change($table, $values = array(), $condition, $parameters = array()) {
		return self::query("UPDATE $table SET ".
		implode(' = ?, ', array_keys($values)).
		" = ? " . $condition,
		array_merge(array_values($values), $parameters));
	}

	// Maže záznamy
	public static function delete($table, $atribute = NULL, $atributeValue = NULL) {
		$sql = "
			DELETE FROM $table 
		";
		if (!empty($atribute)) 
			$sql .= "
			  WHERE $atribute = ?
			";
		
		return self::query($sql,
		!empty($atribute) ? [$atributeValue] : []);
	}
	

}