<?php

/**
 * Fired during plugin activation
 *
 * @link       ricardoshaffer.com
 * @since      1.0.0
 *
 * @package    Resource_Scheduling
 * @subpackage Resource_Scheduling/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Resource_Scheduling
 * @subpackage Resource_Scheduling/includes
 * @author     Ricardo Shaffer <hello@ricardoshaffer.com>
 */
class Resource_Scheduling_Activator {
	
	/**
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$collate = 'DEFAULT CHARSET=utf8';
		$dsn = 'mysql:dbname='.DB_NAME.';host=127.0.0.1';
		$user = DB_USER;
		$password = DB_PASSWORD;
			try {
				$pdo = new PDO($dsn, $user, $password);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							// SQL statement for creating new tables
			$statements = 
			"CREATE TABLE IF NOT EXISTS tickets( 
				ID  INT AUTO_INCREMENT,
				ticket_first_name  VARCHAR(50) NOT NULL, 
				ticket_email VARCHAR(50) NOT NULL, 
				ticket_issue   LONGTEXT NOT NULL,
				ticket_resource   VARCHAR(100) NOT NULL,
				ticket_request   VARCHAR(50) NOT NULL,
				ticket_status enum('OPEN', 'CLOSED', 'IN-PROGRESS') DEFAULT 'OPEN',
				ticket_createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY(ID)
			) $collate;
			CREATE TABLE IF NOT EXISTS ticket_notes (
				note_id   INT AUTO_INCREMENT,
				ticket_ID INT NOT NULL,
				note_createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				note_updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				note_description LONGTEXT NOT NULL,
				PRIMARY KEY (note_id),
				KEY note_ticket_fk (ticket_ID),
				CONSTRAINT note_ticket_fk FOREIGN KEY (ticket_ID) REFERENCES  tickets(ID)
				ON DELETE RESTRICT
			)$collate;";

	// execute SQL statements
	try{

		$pdo->exec($statements);
	} catch (\Throwable $e) {
		die($e->getMessage());
	}
	// end of added tables
						
			return $pdo;

			} catch (PDOException $exception) {
				// If there is an error with the connection, stop the script and display the error.
				exit('Failed to connect to database!');
			}

	}
}
