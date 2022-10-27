<?php
namespace Application\Models;

use Application\Core\Database;

class Partners{

    private int $partner_id = 0;
    private string $partner_name = '';
    private int $user_id = 0;
    private bool $partner_active = false;

    /**
     * @return bool
     */
    public function getPartnerActive(): bool
    {
        return $this->partner_active;
    }

    /**
     * @param bool $partner_active
     */
    public function setPartnerActive(bool $partner_active): void
    {
        $this->partner_active = $partner_active;
    }


    /**
     * @param string $partner_name
     */
    public function setPartnerName(string $partner_name) : void
    {
        $this->partner_name = $partner_name;
    }

    public function getPartnerName() : string{
        return $this->partner_name;
    }

    public function getPartnerId() : int{
        return $this->partner_id;
    }
    
    public function getUserId() : int{
        return $this->user_id;
    }








    /**********************************************************/
    /*                   Fonction OK                          */
    /**********************************************************/


    /**
     * @param int $partner_id
     * @return Partners
     */
    public function getPartnerByPartnerId(int $partner_id) : Partners{
        $partner = Database::q('SELECT * from partners WHERE partner_id = :partner_id', [':partner_id' => $partner_id]);
        if($partner->rowCount() == 1){
            $partner->setFetchMode(\PDO::FETCH_CLASS, 'Application\Models\Partners');
            return $partner->fetch();
        }
        else
            return new Partners();
    }


    public function getPartnerByUserIdv2(int $user_id) : Partners{
        $partner = Database::q('SELECT * from partners WHERE user_id = :user_id', [':user_id' => $user_id]);
        if($partner->rowCount() == 1){

            $partner->setFetchMode(\PDO::FETCH_CLASS, 'Application\Models\Partners');
            return $partner->fetch();
        }
        else{
            return new Partners();
        }
    }

    public function searchB(string $search, int $page, $partner_active = null, $oderBy = null) : array{
        $query = 'Select * from partners WHERE partner_name LIKE :partner_name'
            . ((is_bool($partner_active)) ? ' and partner_active = :partner_active' : '')
            . ' Limit :page, '.NUMBER_ITEM_PER_PAGE;
        if(is_bool($partner_active)){
            $param = [':partner_name' => "%$search%", ':page' => ($page*NUMBER_ITEM_PER_PAGE), ':partner_active' => (($partner_active) ? 1 : 0) ];
        }
        else{
            $param = [':partner_name' => "%$search%", ':page' => ($page*NUMBER_ITEM_PER_PAGE) ];
        }
        $partnerServicesList = Database::q($query, $param);
        if ($partnerServicesList->rowCount() >= 1){
            return array_column($partnerServicesList->fetchAll(\PDO::FETCH_CLASS, 'Application\Models\Partners'), null, 'user_id');
        }
        return array();
    }


    public function partnerSave(): bool
    {
        $query = 'UPDATE partners SET partner_name = :partner_name, partner_active = :partner_active WHERE partner_id = :partner_id';
        try {
            Database::q($query, [
                ':partner_name' => $this->partner_name,
                ':partner_active' => (($this->partner_active) ? 1 : 0),
                ':partner_id' => $this->partner_id
            ]);
        }
        catch (\Exception $e){
            return false;
        }
        return true;
    }

    /**********************************************************/
    /**********************************************************/

















    /**
     * Permet de faire des recherches de partenaire
     * @param string $search        : terme de la recherche
     * @param int $page             : Page en cours
     * @param type $is_active       : partenaire actif ou non
     * @param type $oderBy          : ordre d'affichage (desc / asc)
     * @return array                : la list des retours
     */
    /*public function search(string $search, int $page, $is_active = null, $oderBy = null) : array {
        $query = 'SELECT * FROM partners 
                    LEFT JOIN users ON partners.user_id = users.user_id 
                    WHERE partner_name LIKE :partner_name'
                            . ((is_bool($is_active)) ? ' and is_active = :is_active' : '')
                            . ' Limit :page, '.NUMBER_ITEM_PER_PAGE;
        
        if(is_bool($is_active)){
            $param = [':partner_name' => "%$search%", ':page' => ($page*NUMBER_ITEM_PER_PAGE), ':is_active' => $is_active ];
        }
        else{
            $param = [':partner_name' => "%$search%", ':page' => ($page*NUMBER_ITEM_PER_PAGE) ];
        }
        $partnerSearchList = Database::q($query, $param);
        if ($partnerSearchList->rowCount() >= 1){
            return $partnerSearchList->fetchAll(\PDO::FETCH_OBJ);
        }
        return array();
    }*/

    /**
     * 
     * @param int $partner_id
     * @param int $is_active
     * @return void
     */
    public function updateStatusById(int $partner_id, int $is_active) : void{
        $query = 'UPDATE partners SET is_active = :is_active WHERE partner_id = :partner_id';
        Database::q($query, [':partner_id' => $partner_id, ':is_active' => (($is_active) ? 1 : 0)]);
    }


    /**
     * On à déjà une fonction de recherche utilisation là pour afficher la liste des partenaires
     * @param int $page
     * @return array
     */
    /*public function getAllPartner(int $page = 0) : array{
        return $this->search('', $page);
    }*/
    
    public function getTotalPartner() : int{
        $totalPartner = Database::q('SELECT count(*) as total from partners');
        return $totalPartner->fetch(\PDO::FETCH_OBJ)->total;
    }
    
    public function getPartnerByUserId(int $user_id) : void{
        $partner = Database::q('SELECT * from partners WHERE user_id = :user_id', [':user_id' => $user_id]);
        if($partner->rowCount() == 1){
            $value = $partner->fetch(\PDO::FETCH_OBJ);

            $this->partner_id = $value->partner_id;
            $this->partner_name = $value->partner_name;
        }
    }

    public function createPartner(string $partner_name, int $user_id) : int{
        $query = 'INSERT INTO partners SET partner_name = :partner_name, user_id = :user_id';
        Database::q($query, [':partner_name' => $partner_name,':user_id' => $user_id]);
        return Database::lastInsertId();
    }
}