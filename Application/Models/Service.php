<?php
namespace Application\Models;

use Application\Core\Database;

class Service{

    private int $service_id;
    private string $service_name;
    private string $service_description;
    private bool $service_active;

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
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->service_name;
    }
    /**
     * @param string $service_name
     */
    public function setServiceName(string $service_name): void
    {
        $this->service_name = $service_name;
    }

    /**
     * @return string
     */
    public function getServiceDescription(): string
    {
        return $this->service_description;
    }
    /**
     * @param string $service_description
     */
    public function setServiceDescription(string $service_description): void
    {
        $this->service_description = $service_description;
    }

    /**
     * @return bool
     */
    public function getServiceActive(): bool
    {
        return $this->service_active;
    }
    /**
     * @param bool $service_active
     */
    public function setServiceActive(bool $service_active): void
    {
        $this->service_active = $service_active;
    }

    /**
     * @return array
     */
    public function getAllServices() : array{
        $query = 'SELECT * FROM services';
        $servicesList = Database::q($query);
        if ($servicesList->rowCount() >= 1){
            return array_column($servicesList->fetchAll(\PDO::FETCH_CLASS, 'Application\Models\Service'), null, 'service_id');
        }
        return array();
    }

    /**
     * @param int $service_id
     * @return Service
     */
    public function getService(int $service_id) : Service{
        $query = 'SELECT * FROM services where service_id = :service_id';
        $servicesList = Database::q($query, [
                            ':service_id' => $service_id
                        ]);
        if ($servicesList->rowCount() == 1){
            $servicesList->setFetchMode(\PDO::FETCH_CLASS, 'Application\Models\Service');
            return $servicesList->fetch();
        }
        return new Service();
    }
}