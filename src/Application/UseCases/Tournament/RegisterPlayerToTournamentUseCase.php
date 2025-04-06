<?php
namespace Src\Application\UseCases\Tournament;

use Src\Domain\Repositories\TournamentRepositoryInterface;
use Src\Domain\Repositories\PlayerRepositoryInterface;
use Src\Domain\Repositories\TournamentRegistrationRepositoryInterface;
use Src\Domain\Entities\TournamentRegistration;
use Src\Domain\Enums\TournamentStatus;
use Src\Domain\Exceptions\TournamentNotFoundException;
use Src\Domain\Exceptions\TournamentIsNotPendingException;
use Src\Domain\Exceptions\PlayerNotFoundException;

use Src\Application\DTOs\Tournament\RegisterPlayerTournamentDTO;

class RegisterPlayerToTournamentUseCase {
    public function __construct(
         private TournamentRepositoryInterface $tournamentRepository,
         private PlayerRepositoryInterface $playerRepository,
         private TournamentRegistrationRepositoryInterface $tournamentRegistrationRepositoryInterface
    ){}

    public function execute(RegisterPlayerTournamentDTO $dto): TournamentRegistration 
    {
        $tournamentId = $dto->tournament_id;
        $playerId = $dto->player_id;
        
        $tournament = $this->tournamentRepository->findById($dto->tournament_id);

        if (!$tournament) {
            throw new TournamentNotFoundException("Tournament ".$tournamentId." not found", 404);
        }

        if($tournament->getStatus()->value != TournamentStatus::PENDING->value) {
            throw new TournamentIsNotPendingException("Tournament ".$tournamentId." is not pending", 400);
        }

        $player = $this->playerRepository->findById($playerId);

        if(!$player) {
            throw new PlayerNotFoundException("Player ".$playerId." not found", 400);
        }

        /*
        if($tournament->getCategory()->isMens() && $player->getGender()->isFemale()) {
            throw new FemalePlayerInMaleTournamentException("Player ".$playerId." is a female player in a male tournament", 400);
        }
        else if($tournament->getCategory()->isWomens() && $player->getGender()->isMale()) {
            throw new MalePlayerInFemaleTournamentException("Player ".$playerId." is a male player in a female tournament", 400);
        }
        */

        return $this->tournamentRegistrationRepositoryInterface->save(new TournamentRegistration(null, $player, $tournament));
    }
}
