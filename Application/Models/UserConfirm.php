<?php
namespace Application\Models;

use Application\Core\Database;

class UserConfirm{

    private int $user_id;
    private string $user_key;

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return string
     */
    public function getUserKey(): string
    {
        return $this->user_key;
    }

    /**
     * @param string $user_key
     */
    public function setUserKey(string $user_key): void
    {
        $this->user_key = $user_key;
    }





    /*****************************************************/
    /*               liste des fonctions                 */
    /*****************************************************/

    /**
     * On enregistre une nouvelle entrée
     * @return void
     */
    public function addUserConfirm(): void{
        $query = 'INSERT IGNORE INTO users_confirm set user_id = :user_id, user_key = :user_key';
        Database::q($query, [':user_id' => $this->user_id, ':user_key' => $this->user_key]);
    }

    /**
     * Suppression de la clé en base de donnée
     * @return void
     */
    public function deleteUserConfirm() : void{
        $query = 'DELETE FROM users_confirm WHERE user_id = :user_id';
        Database::q($query, [':user_id' => $this->user_id]);
    }


    /*****************************************************/
    /*               fonctions à refaire                 */
    /*****************************************************/

    /**
     * Check si la clé existe sinon 0
     * @return void
     */
    public function getUserConfirm(string $user_key) : object{

        $sql = 'select * from users_confirm where user_key= :user_key';
        $utilisateur = Database::q($sql,  [':user_key' => $user_key]);
        if($utilisateur->rowCount() == 1){
            //die($this->user_key);
            $userConfirm = $utilisateur->fetch(\PDO::FETCH_OBJ);
            $this->user_id = $userConfirm->user_id;
            $this->user_key = $userConfirm->user_key;
            return $utilisateur;
        }
        else{
            throw new \Exception("Aucun utilisateur ne correspond aux identifiants fournis");
        }
    }
}