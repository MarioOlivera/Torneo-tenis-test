<?php
namespace Src\Infrastructure\Persistence;

use Src\Domain\Entities\TournamentMatch;
use Src\Domain\Repositories\TournamentMatchRepositoryInterface;

class TournamentMatchRepository implements TournamentMatchRepositoryInterface {
    public function __construct(private \mysqli $connection) {}

    public function save(TournamentMatch $match): TournamentMatch {
        $stmt = $this->connection->prepare("
            INSERT INTO tournament_matches 
                (tournament_id, player_one, player_two, player_winner, created_at, updated_at) 
            VALUES 
                (?, ?, ?, ?, ?, ?)
        ");

        if (!$stmt) {
            throw new \Exception("Failed to prepare statement: " . $this->connection->error);
        }

        $tournamentId = $match->getTournament()->getId();
        $playerOneId = $match->getPlayerOne()->getId();
        $playerTwoId = $match->getPlayerTwo()->getId();
        $playerWinnerId = $match->getPlayerWinner()->getId();
        $createdAt = $match->getCreatedAt()->format('Y-m-d H:i:s');
        $updatedAt = $match->getUpdatedAt()->format('Y-m-d H:i:s');

        $stmt->bind_param("iiiiss", 
            $tournamentId, 
            $playerOneId, 
            $playerTwoId, 
            $playerWinnerId, 
            $createdAt, 
            $updatedAt
        );

        $stmt->execute();

        $matchId = $this->connection->insert_id;
        if ($match->getId() === null) {
            $reflection = new \ReflectionClass($match);
            $property = $reflection->getProperty('id');
            $property->setAccessible(true);
            $property->setValue($match, $matchId);
        }

        $stmt->close();
        return $match;
    }
}
