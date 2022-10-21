<?php
namespace Application\Models;

use Application\Core\Database;

class Services{

    public function getAllServices() : array{
        $query = 'SELECT * FROM services';
        $servicesList = Database::q($query);
        if ($servicesList->rowCount() >= 1){
            return $servicesList->fetchAll(\PDO::FETCH_OBJ);
        }
        return array();
    }
}