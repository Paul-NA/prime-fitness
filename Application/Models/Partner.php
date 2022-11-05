<?php
namespace Application\Models;
use Application\Core\Database;
class Partner{
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
    /**
     * @return string
     */
    public function getPartnerName() : string{
        return $this->partner_name;
    }
    /**
     * @return int
     */
    public function getPartnerId() : int{
        return $this->partner_id;
    }
    /**
     * @param int $user_id
     */
    public function setUserId(string $user_id) : void
    {
        $this->user_id = $user_id;
    }
    /**
     * @return int
     */
    public function getUserId() : int{
        return $this->user_id;
    }

    /**********************************************************/
    /*                      Fonction                          */
    /**********************************************************/
    /**
     * @param int $partner_id
     * @return Partner
     */
    public function getPartnerByPartnerId(int $partner_id) : Partner{
        $partner = Database::q('SELECT * from partners WHERE partner_id = :partner_id', [':partner_id' => $partner_id]);
        if($partner->rowCount() == 1){
            $partner->setFetchMode(\PDO::FETCH_CLASS, 'Application\Models\Partner');
            return $partner->fetch();
        }
        else
            return new Partner();
    }
        /**
     * @param int $user_id
     * @return Partner
     */
    public function getPartnerByUserId(int $user_id) : Partner{
        $partner = Database::q('SELECT * from partners WHERE user_id = :user_id', [':user_id' => $user_id]);
        if($partner->rowCount() == 1){

            $partner->setFetchMode(\PDO::FETCH_CLASS, 'Application\Models\Partner');
            return $partner->fetch();
        }
        else{
            return new Partner();
        }
    }
    /**
     * @param string $search_partner_name
     * @param int $page
     * @param $partner_active
     * @param $oderBy
     * @return array
     */
    public function search(string $search_partner_name, int $page, $partner_active = null, $oderBy = null) : array{
        $query = 'Select * from partners WHERE partner_name LIKE :partner_name'
            . ((is_bool($partner_active)) ? ' and partner_active = :partner_active' : '')
            . ' Limit :page, '.NUMBER_ITEM_PER_PAGE;
        if(is_bool($partner_active)){
            $param = [':partner_name' => "%$search_partner_name%", ':page' => ($page*NUMBER_ITEM_PER_PAGE), ':partner_active' => (($partner_active) ? 1 : 0) ];
        }
        else{
            $param = [':partner_name' => "%$search_partner_name%", ':page' => ($page*NUMBER_ITEM_PER_PAGE) ];
        }
        $partnerServicesList = Database::q($query, $param);
        if ($partnerServicesList->rowCount() >= 1){
            return array_column($partnerServicesList->fetchAll(\PDO::FETCH_CLASS, 'Application\Models\Partner'), null, 'user_id');
        }
        return array();
    }
    /**
     * @return bool
     */
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
    /**
     * @return int
     */
    public function getTotalPartner() : int{
        $totalPartner = Database::q('SELECT count(*) as total from partners');
        return $totalPartner->fetch(\PDO::FETCH_OBJ)->total;
    }
    /**
     * @param string $partner_name
     * @param int $user_id
     * @return int
     */
    public function addPartner() : int{
        $query = 'INSERT INTO partners SET partner_name = :partner_name, user_id = :user_id';
        try {

            Database::q($query,
                [
                    ':partner_name' => $this->partner_name,
                    ':user_id' => $this->user_id
                ]
            );
            return Database::lastInsertId();
        }
        catch (\Exception $e){
            return 0;
        }
    }
}