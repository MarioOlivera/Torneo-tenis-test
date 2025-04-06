<?php
namespace Src\Application\DTOs\Tournament;

use Src\Domain\Enums\TournamentCategory;
use Src\Domain\Exceptions\ValidationException;

final class CreateTournamentDTO {
    public function __construct(
        public readonly string $name,
        public readonly TournamentCategory $category,
    ) {}

    public static function fromRequest(array $data): self {
        self::validate($data);

        return new self(
            name: $data['name'],
            category: TournamentCategory::from($data['category_id']),
        );
    }

    private static function validate(array $data): void {
        $errors = [];

        if (!isset($data['name'])) {
            $errors['name'] = 'Name is required';
        }
        else
        {
            if (strlen($data['name']) < 3) {
                $errors['name'] = 'Name must be at least 3 characters';
            }
        }

        if(!isset($data['category_id']))
        {
            $errors['category_id'] = 'category_id is required';
        }
        else if(!is_numeric($data['category_id']))
        {
            $errors['category_id'] = 'category_id must be a number';
        }
        else if(!TournamentCategory::isValid($data['category_id']))
        {
            $errors['category_id'] = 'Invalid category_id';
        }

        if(!empty($errors)) 
        {
            throw new ValidationException(json_encode($errors));
        }
    }
}