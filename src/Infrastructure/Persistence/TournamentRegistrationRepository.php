<?php
namespace Src\Infrastructure\Persistence;

use Src\Domain\Entities\TournamentRegistration;
use Src\Domain\Repositories\TournamentRegistrationRepositoryInterface;

class TournamentRegistrationRepository implements TournamentRegistrationRepositoryInterface {
    public function __construct(private \mysqli $connection) {}

    public function save(TournamentRegistration $tournament_registration): TournamentRegistration {

        $id = $tournament_registration->getId();
        $player_id = $tournament_registration->getPlayer()->getId();
        $tournament_id = $tournament_registration->getTournament()->getId();
        $created_at = $tournament_registration->getCreatedAt()->format('Y-m-d H:i:s');
        $updated_at = $tournament_registration->getUpdatedAt()->format('Y-m-d H:i:s');

        if ($id === null) 
        {
            $stmt = $this->connection->prepare("
                INSERT INTO tournament_registrations (player_id, tournament_id,  created_at, updated_at) 
                VALUES (?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "iiss",
                $player_id,
                $tournament_id,
                $created_at,
                $updated_at
            );
            
            $stmt->execute();
            $tournament_registrationId = $this->connection->insert_id;
            
            $reflection = new \ReflectionClass($tournament_registration);
            $property = $reflection->getProperty('id');
            $property->setAccessible(true);
            $property->setValue($tournament_registration, $tournament_registrationId);
        }
        else
        {
            $stmt = $this->connection->prepare("
                UPDATE tournaments SET 
                    player_id = ?,
                    tournament_id = ?,
                    updated_at = ?
                WHERE id = ?
            ");

            $stmt->bind_param(
                "iisi",
                $player_id,
                $tournament_id,
                $updated_at,
                $id
            );
            
            $stmt->execute();
        }

        return $tournament_registration;
    }

    public function hasTournamentRegistrationWithStatus(int $playerId, int $statusId): bool {
        $stmt = $this->connection->prepare(
            "SELECT COUNT(*) as count FROM tournament_registrations
            INNER JOIN tournaments ON tournament_registrations.tournament_id = tournaments.id
            WHERE tournament_registrations.player_id = ? 
            AND tournaments.status_id = ?"
        );

        if (!$stmt) {
            throw new \Exception("Failed to prepare statement: " . $this->connection->error);
        }
        
        $stmt->bind_param("ii", $playerId, $statusId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return ((int)$row['count']) > 0;
    }
    
}