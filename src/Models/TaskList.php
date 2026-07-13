<?php

namespace Models;

use Override;

class TaskList extends Model
{
    public function __construct(
        int $id, 
        public string $title, 
        public ?string $description = null
    ) {
        parent::__construct($id);
    }

    public function addTask(Task $task): void {
        
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
            'title' => $this->title,
            'description' => $this->description,
            'preview' => $this->preview
        ];
    }
}
