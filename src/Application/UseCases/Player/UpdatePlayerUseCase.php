<?php
namespace Src\Application\UseCases\Player;

use Src\Application\DTOs\Player\UpdatePlayerDTO;
use Src\Domain\Repositories\PlayerRepositoryInterface;
use Src\Domain\Repositories\TournamentRegistrationRepositoryInterface;
use Src\Domain\Entities\Player;
use Src\Domain\Enums\TournamentStatus;
use Src\Domain\Exceptions\PlayerRegisteredInPendingTournamentException;
use Src\Domain\Exceptions\PlayerNotFoundException;

class UpdatePlayerUseCase {
    public function __construct(
        private PlayerRepositoryInterface $playerRepository,
        private TournamentRegistrationRepositoryInterface $tournamentRegistrationRepositoryInterface
    ) {}

    public function execute(UpdatePlayerDTO $dto): Player {
        $player = $this->playerRepository->findById($dto->id);

        if (!$player) {
            throw new PlayerNotFoundException("Player ".$dto->id." not found", 404);
        }

        if (!is_null($dto->name)) {
            $player->setName($dto->name);
        }
        if (!is_null($dto->skill_level)) {
            $player->setSkillLevel($dto->skill_level);
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
        if (!is_null($dto->gender)) {

            if($dto->gender->value !== $player->getGender()->value)
            {
                $hash_pending_tournaments = $this->tournamentRegistrationRepositoryInterface->hasTournamentRegistrationWithStatus($player->getId(), TournamentStatus::PENDING->value);
            
                if($hash_pending_tournaments)
                {
                    throw new PlayerRegisteredInPendingTournamentException();
                }
            }

            $player->setGender($dto->gender);
        }

        return $this->playerRepository->save($player);
    }
}
