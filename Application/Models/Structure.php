<?php
namespace Application\Models;

use Application\Core\Database;

class Structure{

    private int $structure_id = 0;
    private string $structure_name;
    private int $user_id;
    private int $partner_id = 0;
    private bool $structure_active;


    /**
     * @return int
     */
    public function getStructureId(): int
    {
        return $this->structure_id;
    }

    /**
     * @param int $structureId
     */
    public function setStructureId(int $structureId): void
    {
        $this->structure_id = $structureId;
    }

    /**
     * @return string
     */
    public function getStructureName(): string
    {
        return $this->structure_name;
    }

    /**
     * @param string $structure_name
     */
    public function setStructureName(string $structure_name): void
    {
        $this->structure_name = $structure_name;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->user_id;
    }

    /**
     * @param string $user_id
     */
    public function setUserId(string $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return int
     */
    public function getPartnerId(): int
    {
        return $this->partner_id;
    }

    /**
     * @param int $partner_id
     */
    public function setPartnerId(int $partner_id): void
    {
        $this->partner_id = $partner_id;
    }

    /**
     * @return bool
     */
    public function getStructureActive(): bool
    {
        return $this->structure_active;
    }

    /**
     * @param bool $structure_active
     */
    public function setStructureActive(bool $structure_active): void
    {
        $this->structure_active = $structure_active;
    }

    /************************************************************/
    /*                      Fonction                            */
    /************************************************************/


    /**
     * @return bool
     */
    public function saveStructure() : bool{
        $query = 'UPDATE structures SET structure_name = :structure_name, user_id = :user_id, partner_id = :partner_id, structure_active = :structure_active WHERE structure_id = :structure_id';

        try {
            Database::q($query, [
                ':structure_id' => $this->structure_id,
                ':structure_name' => $this->structure_name,
                ':user_id' => $this ->user_id,
                ':partner_id' => $this->partner_id,
                ':structure_active' => ($this->structure_active) ? 1 : 0,
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
    public function addStructure(): int{
        $query = 'INSERT INTO structures SET structure_name = :structure_name, user_id = :user_id, partner_id = :partner_id';
        Database::q($query,
            [
                ':structure_name' => $this->structure_name,
                ':user_id' => $this->user_id,
                ':partner_id' => $this->partner_id
            ]
        );
        return Database::lastInsertId();
    }

    /**
     * @param int $structure_id
     * @return Structure
     */
    public function getStructure(int $structure_id) : Structure{
        $query = 'Select * from structures where structure_id = :structure_id ';
        $structureList = Database::q($query, [
                ':structure_id' => $structure_id
            ]
        );
        if ($structureList->rowCount() == 1){
            $structureList->setFetchMode(\PDO::FETCH_CLASS, 'Application\Models\Structure');
            return $structureList->fetch();
        }
        return new Structure();
    }

    /**
     * @return Structure
     */
    public function getStructureByUserId(int $user_id) : Structure{
        $query = 'SELECT * from structures WHERE user_id = :user_id';
        $structure = Database::q($query, [
            ':user_id' => $user_id
        ]);
        if ($structure->rowCount() == 1){
            $structure->setFetchMode(\PDO::FETCH_CLASS, 'Application\Models\Structure');
            return $structure->fetch();
        }
        else{
            return new Structure();
        }
    }

    /**
     * @param int $partner_id
     * @return array
     */
    public function getStructureListByPartnerId(int $partner_id) : array{
        $query = 'Select * from structures where partner_id = :partner_id ';
        $structureList = Database::q($query, [':partner_id' => $partner_id]);
        if ($structureList->rowCount() >= 1){
            return array_column($structureList->fetchAll(\PDO::FETCH_CLASS, 'Application\Models\Structure'), null, 'user_id');
        }
        return array();
    }

    /**
     * @param array $structureKey
     * @return array
     */
    public function getStructureListByStructuresId(array $structureKey) : array
    {
        $query = 'Select * from structures WHERE structure_id IN ('.implode(', ', $structureKey).') ';
        $partnerServicesList = Database::q($query);
        if ($partnerServicesList->rowCount() >= 1){
            return array_column($partnerServicesList->fetchAll(\PDO::FETCH_CLASS, 'Application\Models\Structure'), null, 'user_id');
        }
        return array();
    }

    /**
     * @param string $search
     * @param int $page
     * @param $structure_active
     * @param $oderBy
     * @return array
     */
    public function search(string $search, int $page, $structure_active = null, $oderBy = null) : array{
        $query = 'Select * from structures WHERE structure_name LIKE :structure_name'
            . ((is_bool($structure_active)) ? ' and structure_active = :structure_active' : '')
            . ' Limit :page, '.NUMBER_ITEM_PER_PAGE;
        if(is_bool($structure_active)){
            $param = [':structure_name' => "%$search%", ':page' => ($page*NUMBER_ITEM_PER_PAGE), ':structure_active' => (($structure_active) ? 1 : 0) ];
        }
        else{
            $param = [':structure_name' => "%$search%", ':page' => ($page*NUMBER_ITEM_PER_PAGE) ];
        }
        $structuresList = Database::q($query, $param);
        if ($structuresList->rowCount() >= 1){
            return array_column($structuresList->fetchAll(\PDO::FETCH_CLASS, 'Application\Models\Structure'), null, 'user_id');
        }
        return array();
    }
}