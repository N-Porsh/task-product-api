<?php
namespace Models;

use PDO;
use PDOException;

class Model
{

    /**
     * @var PDO
     */
    protected $db;

	/**
	 * DB connection constructor.
	 */
    public function __construct()
    {
	    $config = include __DIR__ . '/../config.php';
	    $config = $config['settings']['db'];

        $dsn = 'mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'] . ';port=' . $config['db_port'];
        // note the PDO::FETCH_OBJ, returning object ($result->id) instead of array ($result["id"])
        // @see http://php.net/manual/de/pdo.construct.php
        $options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            PDO::ATTR_PERSISTENT => true,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
        ];

        try {
            // create new PDO db connection
            return $this->db = new PDO($dsn, $config['db_user'], $config['db_pass'], $options);
        } catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
    }


}