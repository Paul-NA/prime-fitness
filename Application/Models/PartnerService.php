<?php
namespace Application\Models;

use Application\Core\Database;

class PartnerService{

    private int $partner_service_id = 0;
    private int $partner_id = 0;
    private int $service_id = 0;
    private int $partner_service_active;

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
     * @return int
     */
    public function getServiceId(): int
    {
        return $this->service_id;
    }

    /**
     * @param int $service_id
     */
    public function setServiceId(int $service_id): void
    {
        $this->service_id = $service_id;
    }

    /**
     * @return int
     */
    public function getPartnerServiceActive(): int
    {
        return $this->partner_service_active;
    }

    /**
     * @param int $partner_service_active
     */
    public function setPartnerServiceActive(int $partner_service_active): void
    {
        $this->partner_service_active = $partner_service_active;
    }






    /*********************************************************************/



    public function updateServiceById(int $structure_id, int $is_active) : void{
        $query = 'UPDATE services SET is_active = :is_active WHERE structure_id = :structure_id';
        Database::q($query, [':structure_id' => $structure_id, ':is_active' => (($is_active) ? 1 : 0)]);
    }



    public function addUpdateRemoveService(int $service_id, int $partner_id, $serviceActive = null)  :bool{
        if($serviceActive === null)
            return self::AddService($service_id, $partner_id);
        elseif($serviceActive == 1 or $serviceActive == 0)
            return self::UpdateService($service_id, $partner_id, $serviceActive);
        else // tout sauf 0 ou 1 ou null
            return self::RemoveService($service_id, $partner_id); // service_id ici repr??sente le partner_service_id
    }

    private function AddService($service_id, $partner_id) : bool{
        $query = 'INSERT INTO partners_services SET service_id = :service_id, partner_id = :partner_id, partner_service_active = 1 ';
        try {
            Database::q($query, [ ':service_id' => $service_id, ':partner_id' => $partner_id ] );
            $return = true;
        }
        catch (\Exception $e){
            $return = false;
        }
        return $return;
    }

    private function UpdateService($service_id, $partner_id, $serviceActive) : bool{
        $query = 'UPDATE partners_services SET partner_service_active = :partner_service_active WHERE partner_id = :partner_id and service_id = :service_id ';
        try {
            Database::q($query, [':partner_service_active' => $serviceActive, ':service_id' => $service_id, ':partner_id' => $partner_id]);
            $return = true;
        }
        catch (\Exception $e){
            $return = false;
        }
        return $return;
    }

    private function RemoveService($partner_service_id, $partner_id) : bool{
        $query = 'DELETE FROM partners_services WHERE partner_service_id = :partner_service_id and partner_id = :partner_id ';
        try{
            Database::q($query, [':partner_service_id' => $partner_service_id, ':partner_id' => $partner_id ] );
            $return = true;
        }
        catch (\Exception $e){
            $return = false;
        }
        return $return;
    }


    /**
     * @param int $partner_service_id
     * @return PartnerService
     */
    public function getPartnerService(int $partner_service_id) : PartnerService{
        $query = 'SELECT * from partners_services WHERE partner_service_id = :partner_service_id';
        $partnerService = Database::q($query, [
            ':partner_service_id' => $partner_service_id
        ]);
        if ($partnerService->rowCount() == 1){
            $partnerService->setFetchMode(\PDO::FETCH_CLASS, 'Application\Models\PartnerService');
            return $partnerService->fetch();
        }
        else{
            return new PartnerService();
        }
    }

/***********************************/

    public function getInformationService(int $partner_service_id){
        $query = 'Select * from partners_services  
                    LEFT JOIN services ON partners_services.service_id = services.service_id 
                        where partner_service_id = :partner_service_id ';
        $structureList = Database::q($query, [':partner_service_id' => $partner_service_id]);
        if ($structureList->rowCount() == 1){
            return $structureList->fetch(\PDO::FETCH_OBJ);
        }
        return null;
    }

    public function getPartnerServiceListByPartnerId(int $partner_id) : array{
        $query = 'Select * from partners_services where partner_id = :partner_id ';
        $partnerServicesList = Database::q($query, [':partner_id' => $partner_id]);
        if ($partnerServicesList->rowCount() >= 1){
            return array_column($partnerServicesList->fetchAll(\PDO::FETCH_CLASS, 'Application\Models\PartnerService'), null, 'service_id'); //$structureList->fetchAll(\PDO::FETCH_OBJ);
        }
        return array();
    }

}