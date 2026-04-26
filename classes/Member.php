<?php
declare(strict_types=1);

class Member extends Person
{
    use TextFormatterTrait;

    protected string $role;
    protected array $achievements;

    public function __construct(
        string $name,
        string $email,
        string $department,
        string $batch,
        string $role,
        array $achievements = []
    ) {
        parent::__construct($name, $email, $department, $batch);
        $this->role = $role;
        $this->achievements = $achievements;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getAchievements(): array
    {
        return $this->achievements;
    }

    public function getSummary(): string
    {
        $summary = $this->name . ' (' . $this->role . ') - ' . $this->department;
        return $this->shortText($summary, 72);
    }
}
