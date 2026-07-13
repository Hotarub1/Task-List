<?php

namespace Models;

use Override;

class Task extends Model
{
    public function __construct(
        int $id, 
        public int $taskListID, 
        public string $title, 
        public ?string $description = null
    ) {
        parent::__construct($id);
    }

    public string $preview {
        get => empty($this->description) ?
            $this->title :
            $this->title . ' - ' . $this->description;
    }

    #[Override]
    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'taskListID' => $this->taskListID,
            'title' => $this->title,
            'description' => $this->description,
            'preview' => $this->preview
        ];
    }
}