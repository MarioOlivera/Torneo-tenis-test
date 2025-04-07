<?php
namespace Src\Application\UseCases\Tournament;

use Src\Domain\Repositories\TournamentRepositoryInterface;
use Src\Domain\Repositories\PlayerRepositoryInterface;
use Src\Domain\Repositories\TournamentMatchRepositoryInterface;
use Src\Domain\Entities\Tournament;
use Src\Domain\Entities\Player;
use Src\Domain\Entities\TournamentMatch;
use Src\Domain\Enums\TournamentStatus;
use Src\Domain\Exceptions\TournamentNotFoundException;
use Src\Domain\Exceptions\TournamentIsNotPendingException;
use Src\Domain\Exceptions\TournamentNotPlayersRegisteredException;
use Src\Domain\Exceptions\TournamentInvalidPlayersCountException;

use Src\Application\DTOs\Tournament\TournamentResultDTO;
use Src\Domain\Collections\TournamentMatchCollection;

class PlayTournamentUseCase {
    public function __construct(
         private TournamentRepositoryInterface $tournamentRepository,
         private PlayerRepositoryInterface $playerRepository,
         private TournamentMatchRepositoryInterface $tournamentMatchRepository
    ){}

    public function execute(int $tournamentId): TournamentResultDTO 
    {
        $tournament = $this->tournamentRepository->findById($tournamentId);

        if (!$tournament) {
            throw new TournamentNotFoundException("Tournament ".$tournamentId." not found", 404);
        }

        if($tournament->getStatus()->value != TournamentStatus::PENDING->value) {
            throw new TournamentIsNotPendingException("Tournament ".$tournamentId." is not pending", 400);
        }

        $players = $this->playerRepository->findPlayersByTournamentId($tournamentId);

        if (empty($players)) {
            throw new TournamentNotPlayersRegisteredException("Tournament ".$tournamentId." not players registered", 400);
        }

        if ((count($players) & (count($players) - 1)) !== 0) {
            throw new TournamentInvalidPlayersCountException("Number of players must be a power of 2", 400);
        }

        shuffle($players); // mezcla los jugadores 

        $tournamentsMatchsCollection = new TournamentMatchCollection();

        // Simulacion del torneo
        while (count($players) > 1) {
            $nextRoundPlayers = [];
            for ($i = 0; $i < count($players); $i += 2) {
                $playerOne = $players[$i];
                $playerTwo = $players[$i + 1];
                
                $winner = $this->simulateMatch($playerOne, $playerTwo, $tournament, null, null);

                $tournamentMatch = new TournamentMatch(
                    null,
                    $tournament,
                    $playerOne,
                    $playerTwo,
                    $winner
                );

                $tournamentMatch = $this->tournamentMatchRepository->save($tournamentMatch);
                $tournamentsMatchsCollection->append($tournamentMatch);
                    
                $nextRoundPlayers[] = $winner;
            }

            $players = $nextRoundPlayers;
        }

        $tournament->setStatus(TournamentStatus::PLAYED);

        $this->tournamentRepository->save($tournament);

        return new TournamentResultDTO(
            $tournament, 
            $tournamentsMatchsCollection, 
            $players[0]
        );
    }
    private function simulateMatch(
        Player $playerOne, 
        Player $playerTwo, 
        Tournament $tournament, 
        ?int $luckOne, 
        ?int $luckTwo
    ): Player {
        $luckOne = $luckOne ?? rand(0, 10);
        $luckTwo = $luckTwo ?? rand(0, 10);
    
        $scoreOne = $playerOne->getSkillLevel() + $luckOne;
        $scoreTwo = $playerTwo->getSkillLevel() + $luckTwo;
    
        if ($tournament->getCategory()->isMens()) {
            $scoreOne += ($playerOne->getStrength() ?? 0) + ($playerOne->getSpeed() ?? 0);
            $scoreTwo += ($playerTwo->getStrength() ?? 0) + ($playerTwo->getSpeed() ?? 0);
        } elseif ($tournament->getCategory()->isWomens()) {
            $scoreOne += ($playerOne->getReactionTime() ?? 0);
            $scoreTwo += ($playerTwo->getReactionTime() ?? 0);
        }
    
        // Recurro a la recursividad en caso de empate
        if ($scoreOne === $scoreTwo) {
            return $this->simulateMatch($playerOne, $playerTwo, $tournament, null, null);
        }
    
        return $scoreOne > $scoreTwo ? $playerOne : $playerTwo;
    }
}
