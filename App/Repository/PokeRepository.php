<?php

namespace App\Repository;

use App\Model\Poke;
use Database;
use DateTime;
use Exception;

class PokeRepository
{
    private UserRepository $userRepository;

    public function __construct(
        private Database $db,
    ) {
        $this->userRepository = new UserRepository($this->db);
    }


    /**
     * Techniskai reikia visur Exception logs etc., bet scope vs time ir cost/benefit, i.e. kiek prie vienos vietos uzsisedesiu ir kiek padarysiu
     */
    public function findPokesByUser(int $limit, ?int $userId): ?array
    {
        $query = "SELECT * FROM pokes WHERE poked_user_id = ?";
        $stmt = $this->db->getConnection()->prepare($query);
    
        if (!$stmt) {
            throw new Exception("Error preparing the statement: " . $this->db->getConnection()->error);
        }
    
        $stmt->bind_param("i", $userId);
    
        if (!$stmt->execute()) {
            throw new Exception("Error executing the statement: " . $stmt->error);
        }
    
        $result = $stmt->get_result();
        $pokes = [];
    
        while ($pokeData = $result->fetch_assoc()) {
            $pokedByUserId = $pokeData['poked_by_user_id'];
            $formatString = "Y-m-d";
            $date = DateTime::createFromFormat($formatString, $pokeData['poked_at']);
    
            $poke = new Poke(
                $pokeData['id'],
                $this->userRepository->findOneById($pokedByUserId),
                $this->userRepository->findOneById($userId),
                $date
            );
    
            $pokes[] = $poke;
        }
    
        $stmt->close();
    
        return $pokes;
    }
    
    
    public function findBy(int $itemsPerPage, int $offset): array
    {
        $query = "SELECT * FROM pokes LIMIT ? OFFSET ?";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bind_param("ii", $itemsPerPage, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $pokes = [];

        while ($pokeData = $result->fetch_assoc()) {
            $pokedByUserId = $pokeData['poked_by_user_id'];
            $pokedUserId = $pokeData['poked_user_id'];
            $formatString = "Y-m-d";
            $date = DateTime::createFromFormat($formatString, $pokeData['poked_at']);

            $poke = new Poke(
                $pokeData['id'],
                $this->userRepository->findOneById($pokedByUserId),
                $this->userRepository->findOneById($pokedUserId),
                $date
            );

            $pokes[] = $poke;
        }

        $stmt->close();

        return $pokes;
    }

    public function countAll(): int
    {
        $query = "SELECT COUNT(*) AS total FROM pokes";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $countData = $result->fetch_assoc();
        $totalPokes = $countData['total'];

        $stmt->close();

        return $totalPokes;
    }


    public function findOneById(int $pokeId): ?Poke
    {
        $query = "SELECT * FROM pokes WHERE id = ?";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bind_param("i", $pokeId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        $pokeData = $result->fetch_assoc();
        $pokedByUserId = $pokeData['poked_by_user_id'];
        $pokedUserId = $pokeData['poked_user_id'];
        $pokedByUser = $this->userRepository->findOneById($pokedByUserId);
        $pokedUser = $this->userRepository->findOneById($pokedUserId);
        $formatString = "Y-m-d";
        $date = DateTime::createFromFormat($formatString, $pokeData['poked_at']);

        $poke = new Poke(
            $pokeData['id'],
            $pokedByUser,
            $pokedUser,
            $date
        );

        $stmt->close();

        return $poke;
    }

    public function findPaginatedSearchedPokes(string $fromDate, string $toDate, string $userName, int $limit, int $offset): array
    {
        if (!$fromDate && !$toDate && !$userName) {
            return $this->findBy($limit, $offset);
        }
    
        $query = "SELECT p.*, u.first_name AS poked_by_username, u2.first_name AS poked_username 
                  FROM pokes p 
                  INNER JOIN users u ON p.poked_by_user_id = u.id
                  INNER JOIN users u2 ON p.poked_user_id = u2.id 
                  WHERE 1=1";
        $queryAndParams = $this->getSearchQuery($fromDate, $toDate, $userName);
        $query .= $queryAndParams['query'];
        $params = $queryAndParams['params'];

        $query .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
    
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $pokes = [];
    
        while ($pokeData = $result->fetch_assoc()) {
            $formatString = "Y-m-d";
            $date = DateTime::createFromFormat($formatString, $pokeData['poked_at']);
    
            $poke = new Poke($pokeData['id'], $this->userRepository->findOneById($pokeData['poked_by_user_id']), $this->userRepository->findOneById($pokeData['poked_user_id']), $date);
            $pokes[] = $poke;
        }
    
        $stmt->close();
    
        return $pokes;
    }

    public function findSearchedPokeCount(string $fromDate, string $toDate, string $userName): int
    {
        if (!$fromDate && !$toDate && !$userName) {
            return $this->countAll();
        }

        $countQuery = "SELECT COUNT(*) AS total_count FROM pokes p
            INNER JOIN users u ON p.poked_by_user_id = u.id
            INNER JOIN users u2 ON p.poked_user_id = u2.id 
            WHERE 1=1";

        $queryAndParams = $this->getSearchQuery($fromDate, $toDate, $userName);

        $countQuery .= $queryAndParams['query'];
        $params = $queryAndParams['params'];

        $stmtCount = $this->db->getConnection()->prepare($countQuery);
        $stmtCount->bind_param(str_repeat('s', count($params)), ...$params);
        $stmtCount->execute();
        $countResult = $stmtCount->get_result();
        $totalCount = $countResult->fetch_assoc()['total_count'];

        return $totalCount;
    }

    private function getSearchQuery(string $fromDate, string $toDate, string $userName): array
    {
        $query = '';
        $params = [];

        if ($fromDate) {
            $query .= " AND p.poked_at >= ?";
            $params[] = $fromDate;
        }

        if ($toDate) {
            $query .= " AND p.poked_at <= ?";
            $params[] = $toDate;
        }

        /**
         * Pasirinkau lengviausia paieskos kelia, bet buvo idomu ieskoti keliu rasti best match kai LIKE ? neveikia ir t.t.
         */
        if ($userName) {
            $query .= " AND (
                (u.first_name LIKE ? OR u2.first_name LIKE ?)
            )";

            $params[] = "%$userName%";
            $params[] = "%$userName%";
        }

        return ['query' => $query, 'params' => $params];
    }
}
