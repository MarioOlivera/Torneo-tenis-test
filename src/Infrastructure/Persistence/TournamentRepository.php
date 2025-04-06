<?php
namespace Src\Infrastructure\Persistence;

use Src\Domain\Entities\Tournament;
use Src\Domain\Repositories\TournamentRepositoryInterface;
use Src\Domain\Enums\TournamentCategory;
use Src\Domain\Enums\TournamentStatus;

class TournamentRepository implements TournamentRepositoryInterface {
    public function __construct(private \mysqli $connection) {}

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
}