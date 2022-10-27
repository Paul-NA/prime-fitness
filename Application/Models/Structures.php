<?php
namespace Application\Models;

use Application\Core\Database;

class Structures{

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


    public function saveStructure(){
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
            //Database::showQuery();
            //die('<pre>'.print_r($e));
            return false;
        }
        return true;
    }

    public function addStructure(): int
    {
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

    public function getStructure(int $structure_id) : Structures{
        $query = 'Select * from structures where structure_id = :structure_id ';
        $structureList = Database::q($query, [
                ':structure_id' => $structure_id
            ]
        );
        if ($structureList->rowCount() == 1){
            $structureList->setFetchMode(\PDO::FETCH_CLASS, 'Application\Models\Structures');
            return $structureList->fetch();
        }
        return new Structures();
    }

    public function getStructureByUserId() : Structures{
        $query = 'SELECT * from structures WHERE user_id = :user_id';
        $structure = Database::q($query, [
            ':user_id' => $this->user_id
        ]);
        if ($structure->rowCount() == 1){
            $structure->setFetchMode(\PDO::FETCH_CLASS, 'Application\Models\Structures');
            return $structure->fetch();
        }
        else{
            return new Structures();
        }
    }

    public function getStructureListByPartnerId(int $partner_id) : array{
        $query = 'Select * from structures where partner_id = :partner_id ';
        $structureList = Database::q($query, [':partner_id' => $partner_id]);
        if ($structureList->rowCount() >= 1){
            return array_column($structureList->fetchAll(\PDO::FETCH_CLASS, 'Application\Models\Structures'), null, 'user_id');
        }
        return array();
    }


    public function getStructureListByStructuresId(array $structureKey) : array
    {
        $query = 'Select * from structures WHERE structure_id IN ('.implode(', ', $structureKey).') ';
        $partnerServicesList = Database::q($query);
        if ($partnerServicesList->rowCount() >= 1){
            return array_column($partnerServicesList->fetchAll(\PDO::FETCH_CLASS, 'Application\Models\Structures'), null, 'user_id');
        }
        return array();
    }

    public function searchB(string $search, int $page, $structure_active = null, $oderBy = null) : array{
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
            return array_column($structuresList->fetchAll(\PDO::FETCH_CLASS, 'Application\Models\Structures'), null, 'user_id');
        }
        return array();
    }
    /************************************************************/
    /*                 Fonction à amélioré                      */
    /************************************************************/

    public function search(string $search, int $page, $is_active = null) : array {
        $query = 'SELECT * FROM structures LEFT JOIN users ON structures.user_id = users.user_id WHERE structure_name LIKE :structure_name'
            . ((is_bool($is_active)) ? ' and is_active = :is_active' : '')
            . ' Limit :page, 30';

        if(is_bool($is_active)){
            $param = [':structure_name' => "%$search%", ':page' => ($page*30), ':is_active' => $is_active ];
        }
        else{
            $param = [':structure_name' => "%$search%", ':page' => ($page*30) ];
        }
        $structureSearchList = Database::q($query, $param);
        if ($structureSearchList->rowCount() >= 1){
            return $structureSearchList->fetchAll(\PDO::FETCH_OBJ);
        }
        return array();
    }


    /****************************************************/
    /*                INUTILE MAINTENANT                */
    /****************************************************/

    /*public function getStructureListByPartnerId(int $partner_id) : array{
        $query = 'Select * from structures LEFT JOIN users ON structures.user_id = users.user_id where partner_id = :partner_id ';
        $structureList = Database::q($query, [':partner_id' => $partner_id]);
        if ($structureList->rowCount() >= 1){
            return $structureList->fetchAll(\PDO::FETCH_OBJ);
        }
        return array();
    }*/
    public function updateStatusById(int $structure_id, int $is_active) : void{
        $query = 'UPDATE structures SET is_active = :is_active WHERE structure_id = :structure_id';
        Database::q($query, [':structure_id' => $structure_id, ':is_active' => (($is_active) ? 1 : 0)]);
    }

    public function createStructure(string $structure_name, int $user_id, int $partner_id) : int{
        $query = 'INSERT INTO structures SET structure_name = :structure_name, user_id = :user_id, partner_id = :partner_id';
        Database::q($query, [':structure_name' => $structure_name,':user_id' => $user_id,':partner_id' => $partner_id]);
        return Database::lastInsertId();
    }

    public function getStructureByStructureId(int $structure_id){
        $structure = Database::q('SELECT * from structures WHERE structure_id = :structure_id', [':structure_id' => $structure_id]);
        if($structure->rowCount() == 1){
            $structure = $structure->fetch(\PDO::FETCH_OBJ);

            $this->structure_id = $structure->structure_id;
            $this->structure_name = $structure->structure_name;
            $this->user_id = $structure->user_id;
            $this->partner_id = $structure->partner_id;
            return $structure;
        }
    }

    public function deleteStructure(int $structure_id) : bool {
        $query = 'delete from structures where structure_id = :structure_id';
        try{
            Database::q($query , [
                ':structure_id' => $structure_id
            ]);
            return true;
        }
        catch (\Exception $e){
            return false;
        }
    }

}