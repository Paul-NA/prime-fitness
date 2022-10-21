<?php
namespace Application\Models;

use Application\Core\Database;

class User{

    /**
     * @var int Identifiant de l'utilisateur
     */
    private int $user_id;
    /**
     * @var string Nom de la personne
     */
    private string $user_firstname;
    /**
     * @var string Prenom de la personne
     */
    private string $user_lastname;
    /**
     * @var string Email de l'utilisateur
     */
    private string $user_mail;
    /**
     * @var string Mot de passe de l'utilisateur
     */
    private string $user_password;
    /**
     * @var int Numéro de téléphone de l'utilisateur
     */
    private int $user_phone;
    /**
     * @var string Adresse de l'utilisateur
     */
    private string $user_address;
    /**
     * @var bool Utilisateur Actif
     */
    private bool $user_active = false;
    /**
     * @var string Date de création de l'utilisateur
     */
    private string $user_created_date;
    /**
     * @var int FK role_id de l'utilisateur
     */
    private int $role_id;

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
    public function getUserFirstname(): string
    {
        return $this->user_firstname;
    }

    /**
     * @param string $user_firstname
     */
    public function setUserFirstname(string $user_firstname): void
    {
        $this->user_firstname = $user_firstname;
    }

    /**
     * @return string
     */
    public function getUserLastname(): string
    {
        return $this->user_lastname;
    }

    /**
     * @param string $user_lastname
     */
    public function setUserLastname(string $user_lastname): void
    {
        $this->user_lastname = $user_lastname;
    }

    /**
     * @return string
     */
    public function getUserMail(): string
    {
        return $this->user_mail;
    }

    /**
     * @param string $user_mail
     */
    public function setUserMail(string $user_mail): void
    {
        $this->user_mail = $user_mail;
    }

    /**
     * @return mixed
     */
    public function getUserPassword()
    {
        return $this->user_password;
    }

    /**
     * @param mixed $user_password
     */
    public function setUserPassword($user_password): void
    {
        $this->user_password = password_hash($user_password, PASSWORD_DEFAULT);
    }

    /**
     * @return int
     */
    public function getUserPhone(): int
    {
        return $this->user_phone;
    }

    /**
     * @param int $user_phone
     */
    public function setUserPhone(int $user_phone): void
    {
        $this->user_phone = $user_phone;
    }

    /**
     * @return string
     */
    public function getUserAddress(): string
    {
        return $this->user_address;
    }

    /**
     * @param string $user_address
     */
    public function setUserAddress(string $user_address): void
    {
        $this->user_address = $user_address;
    }

    /**
     * @return bool
     */
    public function isUserActive(): bool
    {
        return $this->user_active;
    }

    /**
     * @param bool $user_active
     */
    public function setUserActive(bool $user_active): void
    {
        $this->user_active = $user_active;
    }

    /**
     * @return string
     */
    public function getUserCreatedDate(): string
    {
        return $this->user_created_date;
    }

    /**
     * @param string $user_created_date
     */
    public function setUserCreatedDate(string $user_created_date): void
    {
        $this->user_created_date = $user_created_date;
    }

    /**
     * @return int
     */
    public function getRoleId(): int
    {
        return $this->role_id;
    }

    /**
     * @param int $role_id
     */
    public function setRoleId(int $role_id): void
    {
        $this->role_id = $role_id;
    }


    /**
     * Vérifie qu'un utilisateur existe dans la BD
     * 
     * @param string $mail Le login
     * @param string $password Le mot de passe
     * @return l'id de l'utilisateur existe, sinon 0
     */
    public function login(string $mail, string $password) : int{
        $sql = "select * from users where user_mail= :user_mail";
        $utilisateur =  Database::q($sql,  [':user_mail' => $mail]);
        if($utilisateur->rowCount() == 1){
            $user = $utilisateur->fetch(\PDO::FETCH_OBJ);
            return (password_verify($password, $user->user_password)) ? $user->user_id : 0;
        }
        return 0;
    }

    /**
     * Renvoie un utilisateur existant dans la BD
     * 
     * @param string $mail Le mail de l'utilisateur / admin
     * @param string $password Le mot de passe de l'utilisateur / admin
     * @return mixed L'utilisateur
     * @throws Exception Si aucun utilisateur ne correspond aux paramètres
     */
    public function getUser(int $user_id) : object{
        $sql = "select user_id, user_mail, user_password, user_active, user_firstname, user_lastname, user_phone, user_address, role_id from users where user_id=:user_id";
        $utilisateur =  Database::q($sql, [':user_id' => $user_id]);
        if ($utilisateur->rowCount() == 1){
            $user = $utilisateur->fetch(\PDO::FETCH_OBJ);
            $this->user_id = $user->user_id;
            $this->user_firstname = $user->user_firstname;
            $this->user_lastname = $user->user_lastname;
            $this->user_mail = $user->user_mail;
            $this->user_password = $user->user_password;
            $this->user_phone = $user->user_phone;
            $this->user_address = $user->user_address;
            $this->user_active = $user->user_active;
            $this->role_id = $user->role_id;
            return $user;
        }
        else{
            throw new \Exception("Aucun utilisateur ne correspond aux identifiants fournis");
        }
    }

    /**
     * Insère un nouvel user dans la base de donnée
     * @return int
     */
    public function addUser() : int{
        $query = 'INSERT IGNORE INTO users SET user_firstname = :user_firstname, user_lastname = :user_lastname, user_mail = :user_mail, user_password = :user_password, user_phone =:user_phone, user_address = :user_address, user_active =:user_active, role_id = :role_id';
        try {

            Database::q($query,
                [
                    ':user_firstname' => $this->user_firstname,
                    ':user_lastname' => $this->user_lastname,
                    ':user_mail' => $this->user_mail,
                    ':user_password' => password_hash($this->user_password, PASSWORD_DEFAULT),
                    ':user_phone' => $this->user_phone,
                    ':user_address' => $this->user_address,
                    ':user_active' => $this->user_active,
                    ':role_id' => $this->role_id
                ]
            );
            return Database::lastInsertId();
        }
        catch (\Exception $e){
            return 0;
        }
    }

    /**
     * Mise à jour d'un utilisateur dans la base de donnée
     * @return bool|void
     */
    public function updateUser() {
        $query = 'UPDATE users SET 
                 user_firstname = :user_firstname, 
                 user_lastname = :user_lastname, 
                 user_mail = :user_mail, 
                 user_password =:user_password, 
                 user_phone =:user_phone, 
                 user_address = :user_address,
                 user_active =:user_active 
                    WHERE user_id = :user_id';
        try {
            $id_user = Database::q($query,
                [
                    ':user_firstname' => $this->user_firstname,
                    ':user_lastname' => $this->user_lastname,
                    ':user_mail' => $this->user_mail,
                    ':user_phone' => $this->user_phone,
                    ':user_password' => $this->user_password,
                    ':user_address' => $this->user_address,
                    ':user_active' => ($this->user_active) ? 1 : 0,
                    ':user_id' => $this->user_id
                ]
            );
        }
        catch (\Exception $e){
            return false;
        }
        return true;
    }

    public function getMailsByUserListId(array $users_id){
        $query = 'select * from users WHERE user_id IN (:user_id)';
        $result = Database::q($query, [':user_id' => implode(', ', $users_id)]);
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }
}