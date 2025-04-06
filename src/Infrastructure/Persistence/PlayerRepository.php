<?php
namespace Src\Infrastructure\Persistence;

use Src\Domain\Entities\Player;
use Src\Domain\Repositories\PlayerRepositoryInterface;
use Src\Domain\Enums\Gender;

class PlayerRepository implements PlayerRepositoryInterface {
    public function __construct(private \mysqli $connection) {}

    public function findAll(): array
    {
        $query = "SELECT * FROM players";
        $result = $this->connection->query($query);

        $players = [];
        while ($row = $result->fetch_assoc()) {
            $players[] = new Player(
                $row['id'], 
                $row['name'], 
                $row['skill_level'], 
                Gender::from($row['gender_id']), 
                $row['strength'], 
                $row['speed'], 
                $row['reaction_time'], 
                new \DateTimeImmutable($row['created_at']), 
                new \DateTimeImmutable($row['updated_at'])
            );
        }

        return $players;
    }

    public function save(Player $player): Player {

        $id = $player->getId();
        $name = $player->getName();
        $skillLevel = $player->getSkillLevel();
        $genderValue = $player->getGender()->value;
        $strength = $player->getStrength();
        $speed = $player->getSpeed();
        $reactionTime = $player->getReactionTime();
        $created_at = $player->getCreatedAt()->format('Y-m-d H:i:s');
        $updated_at = $player->getUpdatedAt()->format('Y-m-d H:i:s');

        if ($id === null) 
        {
            $stmt = $this->connection->prepare("
                INSERT INTO players (name, skill_level, gender_id, strength, speed, reaction_time, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "siiiiiss",
                $name,
                $skillLevel,
                $genderValue,
                $strength,
                $speed,
                $reactionTime,
                $created_at,
                $updated_at
            );
            
            $stmt->execute();
            $playerId = $this->connection->insert_id;
            
            $reflection = new \ReflectionClass($player);
            $property = $reflection->getProperty('id');
            $property->setAccessible(true);
            $property->setValue($player, $playerId);
        }
        else
        {
            $stmt = $this->connection->prepare("
                UPDATE players SET 
                    name = ?,
                    skill_level = ?,
                    gender_id = ?,
                    strength = ?,
                    speed = ?,
                    reaction_time = ?,
                    updated_at = ?
                WHERE id = ?
            ");

            $stmt->bind_param(
                "siiiissi",
                $name,
                $skillLevel,
                $genderValue,
                $strength,
                $speed,
                $reactionTime,
                $updated_at,
                $id
            );
            
            $stmt->execute();
        }

        return $player;
    }

    public function findById(int $id): ?Player {
        $stmt = $this->connection->prepare("
            SELECT * FROM players WHERE id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        $data = $result->fetch_assoc();
        return new Player(
            $data['id'],
            $data['name'],
            $data['skill_level'],
            Gender::from($data['gender_id']),
            $data['strength'],
            $data['speed'],
            $data['reaction_time'],
            new \DateTimeImmutable($data['created_at']),
            new \DateTimeImmutable($data['updated_at'])
        );
    }

    public function delete(int $id): bool {
        $stmt = $this->connection->prepare("DELETE FROM players WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function findPlayersByTournamentId(int $tournamentId): array {
        $stmt = $this->connection->prepare(
            "SELECT players.* FROM players
            INNER JOIN tournament_registrations ON players.id = tournament_registrations.player_id
            WHERE tournament_registrations.tournament_id = ?"
        );

        if (!$stmt) {
            throw new \Exception("Failed to prepare statement: " . $this->connection->error);
        }
    
        $stmt->bind_param("i", $tournamentId);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $players = [];
    
        while ($row = $result->fetch_assoc()) {
            $player = new \Src\Domain\Entities\Player(
                (int)$row['id'],
                $row['name'],
                (int)$row['skill_level'],
                \Src\Domain\Enums\Gender::from((int)$row['gender_id']),
                isset($row['strength']) ? (int)$row['strength'] : null,
                isset($row['speed']) ? (int)$row['speed'] : null,
                isset($row['reaction_time']) ? (int)$row['reaction_time'] : null,
                new \DateTimeImmutable($row['created_at']),
                new \DateTimeImmutable($row['updated_at'])
            );
            $players[] = $player;
        }
    
        $stmt->close();
        return $players;
    }
}