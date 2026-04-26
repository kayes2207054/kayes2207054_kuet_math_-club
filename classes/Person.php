<?php
declare(strict_types=1);

abstract class Person implements Displayable
{
    protected string $name;
    protected string $email;
    protected string $department;
    protected string $batch;

    public function __construct(string $name, string $email, string $department, string $batch)
    {
        $this->name = $name;
        $this->email = $email;
        $this->department = $department;
        $this->batch = $batch;
    }

    abstract public function getRole(): string;

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getDepartment(): string
    {
        return $this->department;
    }

    public function getBatch(): string
    {
        return $this->batch;
    }
}
