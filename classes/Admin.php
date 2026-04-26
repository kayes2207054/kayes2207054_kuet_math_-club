<?php
declare(strict_types=1);

class Admin extends Member
{
    private array $permissions;

    public function __construct(
        string $name,
        string $email,
        string $department,
        string $batch,
        string $role,
        array $achievements = [],
        array $permissions = ['add_member', 'add_event']
    ) {
        parent::__construct($name, $email, $department, $batch, $role, $achievements);
        $this->permissions = $permissions;
    }

    public function can(string $permission): bool
    {
        return in_array($permission, $this->permissions, true);
    }

    public function getSummary(): string
    {
        return '[Admin] ' . parent::getSummary();
    }
}
