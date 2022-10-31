<?php
namespace Application\Models;

use Application\Core\Database;

class StructureService{

    private int $structure_service_id, $partner_service_id, $structure_id, $is_active;

    /**
     * @return int
     */
    public function getStructureServiceId(): int
    {
        return $this->structure_service_id;
    }

    /**
     * @param int $structure_service_id
     */
    public function setStructureServiceId(int $structure_service_id): void
    {
        $this->structure_service_id = $structure_service_id;
    }

    /**
     * @return int
     */
    public function getPartnerServiceId(): int
    {
        return $this->partner_service_id;
    }

    /**
     * @param int $partner_service_id
     */
    public function setPartnerServiceId(int $partner_service_id): void
    {
        $this->partner_service_id = $partner_service_id;
    }

    /**
     * @return int
     */
    public function getStructureId(): int
    {
        return $this->structure_id;
    }

    /**
     * @param int $structure_id
     */
    public function setStructureId(int $structure_id): void
    {
        $this->structure_id = $structure_id;
    }

    /**
     * @return int
     */
    public function getIsActive(): int
    {
        return $this->is_active;
    }

    /**
     * @param int $is_active
     */
    public function setIsActive(int $is_active): void
    {
        $this->is_active = $is_active;
    }




    /*****************************************************/
    /*               liste des fonctions                 */
    /*****************************************************/

    public function getStructureServiceListByPartnerServiceId(int $partner_service_id) : array{
        $query = 'Select * from structures_services where partner_service_id = :partner_service_id ';
        $structureList = Database::q($query, [':partner_service_id' => $partner_service_id]);
        if ($structureList->rowCount() >= 1){
            return array_column($structureList->fetchAll(\PDO::FETCH_CLASS, 'Application\Models\StructureService'), null, 'structure_id');
        }
        return array();
    }

    public function getStructureServiceListByStructureId(int $structure_id) : array{
        $query = 'Select * from structures_services where structure_id = :structure_id ';
        $structureList = Database::q($query, [':structure_id' => $structure_id]);
        if ($structureList->rowCount() >= 1){
            return array_column($structureList->fetchAll(\PDO::FETCH_CLASS, 'Application\Models\StructureService'), null, 'partner_service_id');
        }
        return array();
    }


    /*****************************************************/
    /*               fonctions à revoir                  */
    /*****************************************************/

    public function addRemoveService(int $service_id, int $structureId , bool $serviceActive) : bool{
        if($serviceActive)
            return self::AddService($service_id, $structureId);
        else
            return self::removeStructureService($service_id, $structureId);
    }

    private function AddService($partner_service_id, $structure_id) : bool{
        $query = 'INSERT INTO structures_services SET partner_service_id = :partner_service_id, structure_id = :structure_id ';
        try {
            Database::q($query, [':partner_service_id' => $partner_service_id, ':structure_id' => $structure_id]);
            return true;
        }
        catch (\Exception $e){
            return false;
        }
    }

    public function addStructureService(){
        $query = 'INSERT INTO structures_services SET partner_service_id = :partner_service_id, structure_id = :structure_id ';
        Database::q($query, [
            ':partner_service_id' => $this->partner_service_id,
            ':structure_id' => $this->structure_id]
        );
    }

    private function removeStructureService($partner_service_id, $structure_id) : bool{
        $query = 'DELETE FROM structures_services WHERE partner_service_id = :partner_service_id and structure_id = :structure_id ';
        try {
            Database::q($query, [':partner_service_id' => $partner_service_id, ':structure_id' => $structure_id]);
            return true;
        }
        catch (\Exception $e){
            return false;
        }
    }

    public function deleteStructureServiceById(int $structure_service_id){
        $query = 'DELETE FROM structures_services WHERE structure_service_id = :structure_service_id';
        Database::q($query, [':structure_service_id' => $structure_service_id]);
    }

}