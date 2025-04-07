<?php
namespace Src\Application\DTOs\Tournament;

use Src\Domain\Exceptions\ValidationException;

final class UpdateTournamentDTO {
    public function __construct(
        public readonly int $id,
        public readonly string $name
    ) {}

    public static function fromRequest(int $id, array $data): self {
        self::validate($data);

        return new self(
            id: $id,
            name: $data['name']
        );
    }

    private static function validate(array $data): void {
        $errors = [];

        if (!isset($data['name']) || empty(trim($data['name']))) {
            $errors['name'] = 'Name is required';
        } else if (strlen(trim($data['name'])) < 3) {
            $errors['name'] = 'Name must be at least 3 characters';
        }

        if(!empty($errors)) 
        {
            throw new ValidationException(json_encode($errors));
        }
    }
}