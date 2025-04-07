<?php 
namespace Src\Application\DTOs\Tournament;

use Src\Domain\Entities\Player;
use Src\Domain\Entities\Tournament;
use Src\Domain\Collections\TournamentMatchCollection;

class TournamentResultDTO {
    public function __construct(
        public readonly Tournament $tournament,
        public readonly TournamentMatchCollection $matches,
        public readonly Player $winner
    ) {} 

    public function toArray(): array {
        return [
            'tournament' => $this->tournament,
            'matches' => $this->proccessMatches(),
            'winner' => $this->winner,
        ];
    }

    private function proccessMatches(): array {
        $processedMatches = [];

        foreach ($this->matches as $match) {
            $matchArray = $match->toArray();

            if (is_array($matchArray) && isset($matchArray['tournament'])) {
                unset($matchArray['tournament']);
            }

            $processedMatches[] = $matchArray;
        }

        return $processedMatches;
    }
}