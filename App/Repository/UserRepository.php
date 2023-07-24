<?php

namespace App\Repository;

use App\Model\Poke;
use App\Model\User;
use Database;
use DateTime;

class UserRepository
{
    public function __construct(private Database $db) {
    }

    /**
     * Jeigu darant didesne programa vardas kind of misleading cia 
     */
    public function findBy(int $limit, int $offset): array
    {
        $query = "SELECT * FROM users LIMIT ?, ?";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bind_param("ii", $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = [];
    
        while ($userData = $result->fetch_assoc()) {
            $user = new User($userData['id'], $userData['username'], $userData['first_name'], $userData['last_name'], $userData['email']);
            $users[] = $user;
        }
    
        $stmt->close();
    
        foreach ($users as $user) {
            $user->setPokes($this->findPokesForUser($user));
        }
    
        return $users;
    }
    
    public function findPokesForUser(User $user): array
    {
        $query = "SELECT * FROM pokes WHERE poked_user_id = ?";
        $stmt = $this->db->getConnection()->prepare($query);
        $userId = $user->getId();
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $pokes = [];

        while ($pokeData = $result->fetch_assoc()) {
            $formatString = "Y-m-d";
            $date = DateTime::createFromFormat($formatString, $pokeData['poked_at']);

            $poke = new Poke($pokeData['id'], $pokeData['poked_by_user_id'], $userId, $date);
            $pokes[] = $poke;
        }
    
        $stmt->close();
    
        return $pokes;
    }
    
    public function countAll(): int
    {
        $query = "SELECT COUNT(*) as total FROM users";
        $result = $this->db->getConnection()->query($query);
        $row = $result->fetch_assoc();

        return $row['total'];
    }

    public function findOneByEmail(string $email): ?User
    {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user) {
            $user = new User($user['id'], $user['username'], $user['first_name'], $user['last_name'], $user['email']);
            $user->setPokes($this->findPokesForUser($user));

            return $user;
        }

        return null;
    }

    public function findOneById(int $userId): ?User
    {
        $query = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user) {
            $user = new User($user['id'], $user['username'], $user['first_name'], $user['last_name'], $user['email']);
            $user->setPokes($this->findPokesForUser($user));

            return $user;
        }

        return null;
    }

    public function searchUsers(string $firstName, int $limit = 10, int $offset = 0): array
    {

        $query = "SELECT * FROM users WHERE first_name LIKE ? LIMIT ?, ?";
        $stmt = $this->db->getConnection()->prepare($query);
        $searchParam = "%$firstName%";
        $stmt->bind_param("sii", $searchParam, $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = [];

        while ($userData = $result->fetch_assoc()) {
            $user = new User($userData['id'], $userData['username'], $userData['first_name'], $userData['last_name'], $userData['email']);
            $users[] = $user;
        }

        $stmt->close();
    
        foreach ($users as $user) {
            $user->setPokes($this->findPokesForUser($user));
        }

        return $users;
    }

    public function countSearchResults(string $firstName): int
    {
        $query = "SELECT COUNT(*) as total FROM users WHERE first_name LIKE ?";
        $stmt = $this->db->getConnection()->prepare($query);
        $searchParam = "%$firstName%";
        $stmt->bind_param("s", $searchParam);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row['total'];
    }
}
