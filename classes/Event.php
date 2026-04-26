<?php
declare(strict_types=1);

class Event implements Displayable
{
    use TextFormatterTrait;

    private string $title;
    private string $date;
    private string $venue;
    private string $type;
    private string $description;

    public function __construct(string $title, string $date, string $venue, string $type, string $description)
    {
        $this->title = $title;
        $this->date = $date;
        $this->venue = $venue;
        $this->type = $type;
        $this->description = $description;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getVenue(): string
    {
        return $this->venue;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isUpcoming(): bool
    {
        return strtotime($this->date) >= strtotime(date('Y-m-d'));
    }

    public function getSummary(): string
    {
        return $this->shortText($this->title . ' at ' . $this->venue, 90);
    }
}
