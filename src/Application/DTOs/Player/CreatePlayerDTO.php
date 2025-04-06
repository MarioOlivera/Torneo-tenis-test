<?php
namespace Src\Application\DTOs\Player;

use Src\Domain\Enums\Gender;
use Src\Domain\Exceptions\ValidationException;

final class CreatePlayerDTO {
    public function __construct(
        public readonly string $name,
        public readonly int $skill_level,
        public readonly Gender $gender,
        public readonly ?int $strength = null,
        public readonly ?int $speed = null,
        public readonly ?int $reaction_time = null
    ) {}

    public static function fromRequest(array $data): self {
        self::validate($data);

        return new self(
            name: $data['name'],
            skill_level: $data['skill_level'],
            gender: Gender::from($data['gender_id']),
            strength: $data['strength'] ?? null,
            speed: $data['speed'] ?? null,
            reaction_time: $data['reaction_time'] ?? null
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

        if(!isset($data['skill_level']))
        {
            $errors['skill_level'] = 'Skill level is required';
        }
        else if(!is_numeric($data['skill_level']))
        {
            $errors['skill_level'] = 'Skill level must be a number';
        }
        else if($data['skill_level'] < 0 || $data['skill_level'] > 100)
        {
            $errors['skill_level'] = 'Skill level must be between 0-100';
        }

        if(!isset($data['gender_id']))
        {
            $errors['gender_id'] = 'gender_id is required';
        }
        else if(!is_numeric($data['gender_id']))
        {
            $errors['gender_id'] = 'gender_id must be a number';
        }
        else if(!Gender::isValid($data['gender_id']))
        {
            $errors['gender_id'] = 'Invalid gender_id';
        }

        if(!isset($data['strength']))
        {
            $errors['strength'] = 'strength is required';
        }
        else if(!is_numeric($data['strength']))
        {
            $errors['strength'] = 'strength must be a number';
        }
        else if($data['strength'] < 0 || $data['strength'] > 100)
        {
            $errors['strength'] = 'strength must be between 0-100';
        }
        
        if(!isset($data['speed']))
        {
            $errors['speed'] = 'speed is required';
        }
        else if(!is_numeric($data['speed']))
        {
            $errors['speed'] = 'speed must be a number';
        }
        else if($data['speed'] < 0 || $data['speed'] > 100)
        {
            $errors['speed'] = 'speed must be between 0-100';
        }
        
        if(!isset($data['reaction_time']))
        {
            $errors['reaction_time'] = 'reaction_time is required';
        }
        else if(!is_numeric($data['reaction_time']))
        {
            $errors['reaction_time'] = 'reaction_time must be a number';
        }
        else if($data['reaction_time'] < 0 || $data['reaction_time'] > 100)
        {
            $errors['reaction_time'] = 'reaction_time must be between 0-100';
        }

        if(!empty($errors)) 
        {
            throw new ValidationException(json_encode($errors));
        }
    }
}