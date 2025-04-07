<?php
namespace Src\Application\DTOs\Tournament;

use Src\Domain\Exceptions\ValidationException;

final class RegisterPlayerTournamentDTO {
    public function __construct(
        public readonly int $tournament_id,
        public readonly int $player_id
    ) {}

    public static function fromRequest(int $tournament_id, array $data): self {
        self::validate($tournament_id, $data);

        return new self(
            tournament_id: $tournament_id,
            player_id: $data['player_id']
        );
    }

    private static function validate(int $tournament_id, array $data): void {
        $errors = [];

        if(!is_numeric($tournament_id)) {
            $errors['tournament_id'] = 'tournament_id must be a number';
        } else if($tournament_id < 0) {
            $errors['tournament_id'] = 'tournament_id must be greater than 0';
        }

        if(!isset($data['player_id'])) {
            $errors['player_id'] = 'player_id is required';
        } else if(!is_numeric($data['player_id'])) {
            $errors['player_id'] = 'player_id must be a number';
        } else if($data['player_id'] < 0) {
            $errors['player_id'] = 'player_id must be greater than 0';
        }

        if(!empty($errors)) {
            throw new ValidationException(json_encode($errors));
        }
    }
}
