<?php
namespace Src\Infrastructure\Persistence;

use Src\Domain\Entities\TournamentRegistration;
use Src\Domain\Repositories\TournamentRegistrationRepositoryInterface;
use Src\Domain\Entities\Player;
use Src\Domain\Entities\Tournament;

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

    public function getByPlayerIdAndTournamentId(int $player_id, int $tournament_id): ?TournamentRegistration {
        $stmt = $this->connection->prepare(
            "SELECT 
                tournament_registrations.*,
                players.id as players_id,
                players.name as players_name,
                players.skill_level as players_skill_level,
                players.strength as players_strength,
                players.speed as players_speed, 
                players.reaction_time as players_reaction_time,
                players.gender_id as players_gender_id,
                players.created_at as players_created_at,
                players.updated_at as players_updated_at,
                tournaments.id as tournaments_id,
                tournaments.name as tournaments_name,
                tournaments.category_id as tournaments_category_id,
                tournaments.status_id as tournaments_status_id,
                tournaments.created_at as tournaments_created_at,
                tournaments.updated_at as tournaments_updated_at
            FROM tournament_registrations
            INNER JOIN players ON tournament_registrations.player_id = players.id
            INNER JOIN tournaments ON tournament_registrations.tournament_id = tournaments.id
            WHERE tournament_registrations.player_id = ? AND tournament_registrations.tournament_id = ?
            LIMIT 1"
        );
        
        $stmt->bind_param("ii", $player_id, $tournament_id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            $stmt->close();
            return null;
        }
        
        $row = $result->fetch_assoc();
        $stmt->close();
        
        $player = new Player(
            (int)$row['players_id'],
            $row['players_name'],
            (int)$row['players_skill_level'],
            \Src\Domain\Enums\Gender::from((int)$row['players_gender_id']),
            isset($row['players_strength']) ? (int)$row['players_strength'] : null,
            isset($row['players_speed']) ? (int)$row['players_speed'] : null,
            isset($row['players_reaction_time']) ? (int)$row['players_reaction_time'] : null,
            new \DateTimeImmutable($row['players_created_at']),
            new \DateTimeImmutable($row['players_updated_at'])
        );
        
        $tournament = new Tournament(
            (int)$row['tournaments_id'],
            $row['tournaments_name'],
            \Src\Domain\Enums\TournamentCategory::from((int)$row['tournaments_category_id']),
            \Src\Domain\Enums\TournamentStatus::from((int)$row['tournaments_status_id']),
            new \DateTimeImmutable($row['tournaments_created_at']),
            new \DateTimeImmutable($row['tournaments_updated_at'])
        );
        
        return new TournamentRegistration(
            (int)$row['id'],
            $player,
            $tournament,
            new \DateTimeImmutable($row['created_at']),
            new \DateTimeImmutable($row['updated_at'])
        );
    }    
}