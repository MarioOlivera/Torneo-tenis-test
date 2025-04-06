<?php
namespace Src\Application\UseCases\Player;

use Src\Application\DTOs\Player\UpdatePlayerDTO;
use Src\Domain\Repositories\PlayerRepositoryInterface;
use Src\Domain\Entities\Player;

class UpdatePlayerUseCase {
    public function __construct(
        private PlayerRepositoryInterface $repository
    ) {}

    public function execute(UpdatePlayerDTO $dto): Player {
        // Buscar el jugador existente
        $player = $this->repository->findById($dto->id);
        if (!$player) {
            throw new \Src\Domain\Exceptions\DomainException(
                "Player not found", 
                \Src\Domain\Enums\ErrorCode::VALIDATION, 
                404
            );
        }

        // Actualizar los atributos solo si vienen en el DTO
        if (!is_null($dto->name)) {
            $player->setName($dto->name);
        }
        if (!is_null($dto->skill_level)) {
            $player->setSkillLevel($dto->skill_level);
        }
        if (!is_null($dto->gender)) {
            $player->setGender($dto->gender);
        }
        if (!is_null($dto->strength)) {
            $player->setStrength($dto->strength);
        }
        if (!is_null($dto->speed)) {
            $player->setSpeed($dto->speed);
        }
        if (!is_null($dto->reaction_time)) {
            $player->setReactionTime($dto->reaction_time);
        }

        return $this->repository->save($player);
    }
}
