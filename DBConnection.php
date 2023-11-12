<?php

if(!is_dir(__DIR__.'./db'))
    mkdir(__DIR__.'./db');

if(!defined('db_file')) define('db_file',__DIR__.'./db/task_db.db');

if(!defined('tZone')) define('tZone',"America/Sao_Paulo");
if(!defined('dZone')) define('dZone',ini_get('date.timezone'));


Class DBConnection extends SQLite3{
    protected $db;
    function __construct(){

        $this->open(db_file);
        $this->exec("PRAGMA foreign_keys = ON;");

        $this->exec("CREATE TABLE IF NOT EXISTS `user_list` (
            `user_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `fullname` INTEGER NOT NULL,
            `username` TEXT NOT NULL,
            `password` TEXT NOT NULL,
            `type` TINYINT(1) NOT NULL Default 0,
            `status` TINYINT(1) NOT NULL Default 0,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"); 
        $this->exec("CREATE TABLE IF NOT EXISTS `task_list` (
            `task_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `user_id` INTEGER NOT NULL,
            `assigned_id` INTEGER NOT NULL,
            `title` TEXT NOT NULL,
            `description` TEXT NOT NULL,
            `status` TINYINT(2) NOT NULL Default 0,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(`user_id`) REFERENCES `user_list`(`user_id`),
            FOREIGN KEY(`assigned_id`) REFERENCES `user_list`(`user_id`)
        )");
        $this->exec("CREATE TABLE IF NOT EXISTS `task_report` (
            `report_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `task_id` INTEGER NOT NULL,
            `description` TEXT NOT NULL,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(`task_id`) REFERENCES `task_list`(`task_id`)
        )");
        $this->exec("CREATE TABLE IF NOT EXISTS `comment_list` (
            `report_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `task_id` INTEGER NOT NULL,
            `user_id` INTEGER NOT NULL,
            `comment` TEXT NOT NULL,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(`task_id`) REFERENCES `task_list`(`task_id`)
        )");
        $this->exec("INSERT OR IGNORE INTO `user_list` VALUES (1, 'Administrator', 'admin', '$2y$10\$Aj/jjNbcT1vNZrp.9ELpheF9rgjP9RInWb8RSuTGAKcoKJE26HCb6', 1, 1, CURRENT_TIMESTAMP)");

    }
    public function updateUser($user_id, $newFullname) {
        $query = "UPDATE user_list 
                  SET fullname = :newFullname
                  WHERE user_id = :user_id";

        $statement = $this->prepare($query);
        $statement->bindParam(':newFullname', $newFullname);
        $statement->bindParam(':user_id', $user_id);

        if ($statement->execute()) {
            return true; // Atualização bem-sucedida
        } else {
            return false; // Erro na atualização
        }
    }
    function __destruct(){
         $this->close();
    }
}

$conn = new DBConnection();


$conn->updateUser($user_id = 1, $newFullname = 'Administrador');
$conn->updateUser($user_id = 2, $newFullname = 'Teste01');
$conn->updateUser($user_id = 3, $newFullname = 'Teste2');