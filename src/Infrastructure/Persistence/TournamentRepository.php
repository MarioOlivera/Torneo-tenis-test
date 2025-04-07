<?php
namespace Src\Infrastructure\Persistence;

use Src\Domain\Entities\Tournament;
use Src\Domain\Repositories\TournamentRepositoryInterface;
use Src\Domain\Enums\TournamentCategory;
use Src\Domain\Enums\TournamentStatus;

class TournamentRepository implements TournamentRepositoryInterface {
    public function __construct(private \mysqli $connection) {}

    public function findAll(?string $start_date, ?string $end_date, ?int $category_id, ?int $status_id): array {
        $query = "SELECT * FROM tournaments";
        $conditions = [];
        $params = [];
        $types = "";
    
        if ($start_date !== null) {
            $conditions[] = "DATE(created_at) >= ?";
            $types .= "s";
            $params[] = $start_date;
        }
    
        if ($end_date !== null) {
            $conditions[] = "DATE(created_at) <= ?";
            $types .= "s";
            $params[] = $end_date;
        }
    
        if ($category_id !== null) {
            $conditions[] = "category_id = ?";
            $types .= "i";
            $params[] = $category_id;
        }
    
        if ($status_id !== null) {
            $conditions[] = "status_id = ?";
            $types .= "i";
            $params[] = $status_id;
        }
    
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
    
        $stmt = $this->connection->prepare($query);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
    
        $tournaments = [];
        while ($row = $result->fetch_assoc()) {
            $tournaments[] = new Tournament(
                (int)$row['id'],
                $row['name'],
                TournamentCategory::from((int)$row['category_id']),
                TournamentStatus::from((int)$row['status_id']),
                new \DateTimeImmutable($row['created_at']),
                new \DateTimeImmutable($row['updated_at'])
            );
        }

        $stmt->close();
    
        return $tournaments;
    }

    public function findById(int $id): ?Tournament {
        $stmt = $this->connection->prepare("
            SELECT * FROM tournaments WHERE id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        $data = $result->fetch_assoc();

        $stmt->close();

        return new Tournament(
            $data['id'],
            $data['name'],
            TournamentCategory::from($data['category_id']),
            TournamentStatus::from($data['status_id']),
            new \DateTimeImmutable($data['created_at']),
            new \DateTimeImmutable($data['updated_at'])
        );
    }

    public function updateStatus(int $id, int $status_id) : bool {
        $stmt = $this->connection->prepare("
            UPDATE tournaments 
            SET status_id = ?, updated_at = ? 
            WHERE id = ?
        ");
    
        $updatedAt = (new \DateTimeImmutable())->format('Y-m-d H:i:s');
        $stmt->bind_param("isi", $status_id, $updatedAt, $id);
        $stmt->execute();
    
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
    
        return $affectedRows > 0;
    }

    public function save(Tournament $tournament): Tournament {

        $id = $tournament->getId();
        $name = $tournament->getName();
        $category_id = $tournament->getCategory()->value;
        $status_id = $tournament->getStatus()->value;
        $created_at = $tournament->getCreatedAt()->format('Y-m-d H:i:s');
        $updated_at = $tournament->getUpdatedAt()->format('Y-m-d H:i:s');

        if ($id === null) 
        {
            $stmt = $this->connection->prepare("
                INSERT INTO tournaments (name, category_id, status_id, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "siiss",
                $name,
                $category_id,
                $status_id,
                $created_at,
                $updated_at
            );
            
            $stmt->execute();
            $tournamentId = $this->connection->insert_id;
            
            $reflection = new \ReflectionClass($tournament);
            $property = $reflection->getProperty('id');
            $property->setAccessible(true);
            $property->setValue($tournament, $tournamentId);
        }
        else
        {
            $stmt = $this->connection->prepare("
                UPDATE tournaments SET 
                    name = ?,
                    category_id = ?,
                    status_id = ?,
                    updated_at = ?
                WHERE id = ?
            ");

            $stmt->bind_param(
                "siisi",
                $name,
                $category_id,
                $status_id,
                $updated_at,
                $id
            );
            
            $stmt->execute();
        }

        return $tournament;
    }
}