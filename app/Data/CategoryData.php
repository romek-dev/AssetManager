<?php

namespace App\Data;

use App\Models\Category;
use Spatie\LaravelData\Data;

class CategoryData extends Data
{
    public function __construct(
        public int $id,
        public string $name
    ) {
    }

    public static function fromModel(Category|null $category): self|null
    {
        return new self(
            $category->id,
            $category->name
        );
    }
}
