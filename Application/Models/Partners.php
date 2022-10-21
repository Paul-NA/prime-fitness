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
    public function partnerActive(): bool
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

    /**
     * Permet de faire des recherches de partenaire
     * @param string $search        : terme de la recherche
     * @param int $page             : Page en cours
     * @param type $is_active       : partenaire actif ou non
     * @param type $oderBy          : ordre d'affichage (desc / asc)
     * @return array                : la list des retours
     */
    public function search(string $search, int $page, $is_active = null, $oderBy = null) : array {
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
    }
    
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

    public function partnerSave(){
        $query = 'UPDATE partners SET partner_name = :partner_name, is_active = :is_active WHERE partner_id = :partner_id';
        try {
            Database::q($query, [
                ':partner_name' => $this->partner_name,
                ':is_active' => (($this->partner_active) ? 1 : 0),
                ':partner_id' => $this->partner_id
            ]);
        }
        catch (\Exception $e){
            //Database::showQuery();
            //die('<pre>'.print_r($e));
            return false;
        }
        return true;
    }

    /**
     * On à déjà une fonction de recherche utilisation là pour afficher la liste des partenaires
     * @param int $page
     * @return array
     */
    public function getAllPartner(int $page = 0) : array{
        return $this->search('', $page);
    }
    
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
    
    public function getPartnerByPartnerId(int $partner_id){
        $partner = Database::q('SELECT * from partners WHERE partner_id = :partner_id', [':partner_id' => $partner_id]);
        if($partner->rowCount() == 1){
            $value = $partner->fetch(\PDO::FETCH_OBJ);

            $this->partner_id = $value->partner_id;
            $this->partner_name = $value->partner_name;
            $this->user_id = $value->user_id;
            $this->partner_active = $value->is_active;
            return $value;
        }
        return [];
    }
    
    
    public function createPartner(string $partner_name, int $user_id) : int{
        $query = 'INSERT INTO partners SET partner_name = :partner_name, user_id = :user_id';
        Database::q($query, [':partner_name' => $partner_name,':user_id' => $user_id]);
        return Database::lastInsertId();
    }
}