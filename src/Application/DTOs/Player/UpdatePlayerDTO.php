<?php
namespace Src\Application\DTOs\Player;

use Src\Domain\Enums\Gender;
use Src\Domain\Exceptions\ValidationException;

final class UpdatePlayerDTO {
    public function __construct(
        public readonly int $id,
        public readonly ?string $name = null,
        public readonly ?int $skill_level = null,
        public readonly ?Gender $gender = null,
        public readonly ?int $strength = null,
        public readonly ?int $speed = null,
        public readonly ?int $reaction_time = null
    ) {}

    public static function fromRequest(int $id, array $data): self {
        self::validate($data);

        return new self(
            id: $id,
            name: $data['name'] ?? null,
            skill_level: isset($data['skill_level']) ? (int)$data['skill_level'] : null,
            gender: isset($data['gender_id']) ? Gender::from($data['gender_id']) : null,
            strength: isset($data['strength']) ? (int)$data['strength'] : null,
            speed: isset($data['speed']) ? (int)$data['speed'] : null,
            reaction_time: isset($data['reaction_time']) ? (int)$data['reaction_time'] : null
        );
    }

    private static function validate(array $data): void {
        $errors = [];

        if (isset($data['name'])) {
            if (strlen(trim($data['name'])) < 3) {
                $errors['name'] = 'Name must be at least 3 characters';
            }
        }

        if (isset($data['skill_level'])) {
            if (!is_numeric($data['skill_level'])) {
                $errors['skill_level'] = 'Skill level must be a number';
            } elseif ($data['skill_level'] < 0 || $data['skill_level'] > 100) {
                $errors['skill_level'] = 'Skill level must be between 0-100';
            }
        }

        if (isset($data['gender_id'])) {
            if (!is_numeric($data['gender_id'])) {
                $errors['gender_id'] = 'gender_id must be a number';
            } elseif (!Gender::isValid($data['gender_id'])) {
                $errors['gender_id'] = 'Invalid gender_id';
            }
        }

        if (isset($data['strength'])) {
            if (!is_numeric($data['strength'])) {
                $errors['strength'] = 'strength must be a number';
            } elseif ($data['strength'] < 0 || $data['strength'] > 100) {
                $errors['strength'] = 'strength must be between 0-100';
            }
        }

        if (isset($data['speed'])) {
            if (!is_numeric($data['speed'])) {
                $errors['speed'] = 'speed must be a number';
            } elseif ($data['speed'] < 0 || $data['speed'] > 100) {
                $errors['speed'] = 'speed must be between 0-100';
            }
        }

        if (isset($data['reaction_time'])) {
            if (!is_numeric($data['reaction_time'])) {
                $errors['reaction_time'] = 'reaction_time must be a number';
            } elseif ($data['reaction_time'] < 0 || $data['reaction_time'] > 100) {
                $errors['reaction_time'] = 'reaction_time must be between 0-100';
            }
        }

        if (!empty($errors)) {
            throw new ValidationException(json_encode($errors));
        }
    }
}
