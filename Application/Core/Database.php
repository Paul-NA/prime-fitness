<?php
namespace Application\Core;

class Database
{

	protected static $db = null;
	protected static $stmt;

	public static int $nbrQuery = 0;
	public static array $listQuery = [];

	public static function getInstance()
    {
		if(null === static::$db) {
			static::$db = new \PDO('mysql:dbname='.DATABASE['DBNAME'].';host='.DATABASE['HOST'], DATABASE['USER'], DATABASE['PASSWORD']);
			static::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			static::$db->query('SET NAMES utf8;');
		}
		return static::$db;
	}

    /**
     * Execute des requêtes avec la methode exec
     */
    public static function qexec(string $query)
    {
        static::getInstance();
        $stmt = static::$db->exec($query);
        self::addShowQuery($query, null);
        return $stmt;
    }
        
    /**
     * exec prepare query
     */
	public static function q(string $query, array $values = array())
    {
		static::getInstance();
		$stmt = static::$db->prepare($query);
		static::qBindValues($stmt, $values);
		self::addShowQuery($query, $values);
		return $stmt;
	}

    /**
     * Vérifie si une table existe
     */
	public static function checkTable(string $table) : bool
    {
		$query = 'SHOW TABLES LIKE :table';
		$request = Database::q($query, array(':table' => $table));
		return $request->rowCount() ==1;
	}

    /**
     * Ajoute la requête dans le système d'affichage
     */
	private static function addShowQuery($req, $values) : void
    {
		if(is_array($values)){
			foreach ($values as $key => $value){
				$req = str_replace($key, ((is_numeric($value)) ? $value : '"'.addslashes($value).'"'), $req);
			}
		}
		self::$listQuery[] = $req;
	}

    /**
     * Retourne la liste de toutes les requêtes de la page
     */
	public static function showQuery(): array
    {
		return self::$listQuery;
	}

    /**
     * Retourne le dernier id inséré dans la base de donnée durant le dernier insert
     */
    public static function lastInsertId(): int
    {
        return static::$db->lastInsertId();
    }

	protected static function qBindValues(\PDOStatement $stmt, array $values) : void
    {
		//var_dump($values);
		foreach($values as $placeholder => $val) {
			if(is_array($val)) {
				static::qBindValues($stmt, $val);
			}
			else {
				$stmt->bindValue($placeholder, $val, ((is_numeric($val)) ? \PDO::PARAM_INT : \PDO::PARAM_STR));
			}
		}
		self::$nbrQuery++;
		$stmt->execute();
	}


    /**
     * Permet d'afficher le nombre de requêtes par page
     */
	public static function showNbrQuery(): int
    {
		return self::$nbrQuery;
	}


    /**************************************************/
    /** (idée) Gestion des transactions */
    /**
     * Debut d'une transaction
     */
    public static function start_transaction()
    {
        static::getInstance();
        $stmt = static::$db->exec("SET AUTOCOMMIT=0");
        $stmt = static::$db->exec("BEGIN");
        self::addShowQuery("SET AUTOCOMMIT=0", null);
        self::addShowQuery("BEGIN", null);
        return $stmt;
    }

    /**
     * transaction fini on commit
     */
    public static function end_transaction_commit()
    {
        static::getInstance();
        $stmt = static::$db->exec("COMMIT");
        $stmt = static::$db->exec("SET AUTOCOMMIT=1");
        self::addShowQuery("COMMIT", null);
        self::addShowQuery("SET AUTOCOMMIT=1", null);
        return $stmt;
    }

    /**
     * La transaction à une erreur, on fait un rollback
     */
    public static function end_transaction_rollback()
    {
        static::getInstance();
        $stmt = static::$db->exec("ROLLBACK");
        $stmt = static::$db->exec("SET AUTOCOMMIT=1");
        self::addShowQuery("COMMIT", null);
        self::addShowQuery("SET AUTOCOMMIT=1", null);
        return $stmt;
    }
}