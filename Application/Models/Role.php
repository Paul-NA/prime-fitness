<?php
namespace Application\Models;

use Application\Core\Database;

class Role{

    private int $role_id;
    private string $role_name;

    public function setRoleId(): int
    {
        return $this->role_id;
    }

    public function sertRoleName(): string
    {
        return $this->role_name;
    }

    public function AddRoles(string $role_name) : void {
        Database::q('INSERT IGNORE INTO roles SET role_name = :role_name', [':role_name' => $role_name ]);
    }

    public function UpdateRoles(int $role_id, string $role_name) : void {
        Database::q('UPDATE roles SET role_name = :role_name WHERE role_id = :role_id', [':role_name' => $role_name, ':role_id' => $role_id]);
    }
}