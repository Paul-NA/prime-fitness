<?php
namespace Application\Models;

use Application\Core\Database;

class Log{

    private int $log_id;
    private string $log_type;
    private int $log_type_id;
    private string $log_time;
    private string $log_text;
    private int $user_id;

    /**
     * @return int
     */
    public function getLogId(): int
    {
        return $this->log_id;
    }
    /**
     * @param int $log_id
     */
    public function setLogId(int $log_id): void
    {
        $this->log_id = $log_id;
    }

    /**
     * @return string
     */
    public function getLogType(): string
    {
        return $this->log_type;
    }
    /**
     * @param string $log_type
     */
    public function setLogType(string $log_type): void
    {
        $this->log_type = $log_type;
    }

    /**
     * @return int
     */
    public function getLogTypeId(): int
    {
        return $this->log_type_id;
    }
    /**
     * @param int $log_type_id
     */
    public function setLogTypeId(int $log_type_id): void
    {
        $this->log_type_id = $log_type_id;
    }

    /**
     * @return string
     */
    public function getLogTime(): string
    {
        return $this->log_time;
    }

    /**
     * @return string
     */
    public function getLogText(): string
    {
        return $this->log_text;
    }
    /**
     * @param string $log_text
     */
    public function setLogText(string $log_text): void
    {
        $this->log_text = $log_text;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }
    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }



    /**
     * Ajoute un log, et retourne un bool si success ou erreur
     * @return bool
     */
    public function addLog() : bool {
        $query = 'INSERT IGNORE INTO logs SET log_type = :log_type, log_type_id = :log_type_id, log_text = :log_text, user_id = :user_id';
        try {
            Database::q($query,
                [
                    ':log_type' => $this->log_type,
                    ':log_type_id' => $this->log_type_id,
                    ':log_text' => $this->log_text,
                    ':user_id' => $this->user_id
                ]
            );
            return true;
        }
        catch (\Exception $e){
            return false;
        }
    }

    /**
     * On mes jamais ?? jours un log !
     * @param string $logType
     * @param int $logTypeId
     * @param string $logText
     * @return void
     */
    public function updateLogs(string $logType, int $logTypeId,string $logText) : void {}

    /**
     * Savoir le nombre de logs
     * 
     * @param int $user_id
     * @return int
     */
    public function countLogs(int $user_id): int{
        $query = 'select count(*) from logs where user_id = :user_id';
        try {
            $count = Database::q($query, [
               ':id_user' => $user_id
            ]);

            return $count->fetch();
        }
        catch (\Exception $e){
            return 0;
        }
    }
    /**
     * Voir l'int??gralit?? des logs
     * 
     * @param int $user_id
     * @param type $max_log
     * @param type $page
     * @return array
     */
    public function showLogs(int $user_id, $max_log, $page): array{
        $sql = "select log_text, log_time from logs where user_id=:user_id";
        $utilisateur =  Database::q($sql, [':user_id' => $user_id]);
        $user = $utilisateur->fetchAll();
            
        return $user;  // Acc??s ?? la premi??re ligne de r??sultat
    }
    
    /**
     * On ne delete jamais un log !
     * @param int $log_id
     * @return bool
     */
    public function deleteLogs(int $log_id) : bool {
        $query = 'delete from logs where log_id = :log_id';
        try{
            Database::q($query, [
                ':log_id' => $log_id
            ]);
            return true;
        }
        catch (\Exception $e){
            return false;
        }
    }
}