<?php

namespace App\Controllers;

use App\Repository\PokeRepository;
use App\Repository\UserRepository;
use Database;
use Session;

class PokeController extends \Controller 
{
    private $db;
    private PokeRepository $pokeRepository;
    private UserRepository $userRepository;
    const POKES_PER_PAGE = 8;

    public function __construct()
    {
        $this->db = new Database();
        $this->pokeRepository = new PokeRepository($this->db);
        $this->userRepository = new UserRepository($this->db);
    }

    public function index(): void
    {
        $userName = isset($_GET['userName']) ? $_GET['userName'] : '';
        $toDate = isset($_GET['toDate']) ? $_GET['toDate'] : '';
        $fromDate = isset($_GET['fromDate']) ? $_GET['fromDate'] : '';

        if ($userName || $toDate || $fromDate) {
            $totalPokes = $this->pokeRepository->findSearchedPokeCount($fromDate, $toDate, $userName);
        } else {
            $totalPokes = $this->pokeRepository->countAll();
        }
        
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $totalPages = ceil($totalPokes / self::POKES_PER_PAGE);

        if ($currentPage > $totalPages && $currentPage != 0) {
            $currentPage = $totalPages;
        }

        if ($userName || $toDate || $fromDate) {
            $pokes = $this->pokeRepository->findPaginatedSearchedPokes($fromDate, $toDate, $userName, self::POKES_PER_PAGE, self::POKES_PER_PAGE * ($currentPage - 1));
        } else {
            $pokes = $this->pokeRepository->findBy(self::POKES_PER_PAGE, self::POKES_PER_PAGE * ($currentPage - 1));
        }

        $this->render('pages/pokes/index', [
            'pokes'        => $pokes,
            'totalPages'   => $totalPages,
            'currentPage'  => $currentPage,
            'currentQuery' => $this->getCurrentQuery($fromDate, $toDate, $userName),
            'userName'     => $userName,
            'toDate'       => $toDate,
            'fromDate'     => $fromDate
        ]);
    }


    public function addPoke() 
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['poked_user_id'])) {
            $pokedUserId = intval($_POST['poked_user_id']);
            $userId = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

            if ($pokedUserId == $userId) {
                http_response_code(400);
                echo 'Bad request.';
            }

            if ($userId === 0) {
                http_response_code(403);
                echo 'Unauthorized access.';
                exit();
            }

            $pokeDate = date('Y-m-d');
            $query = "INSERT INTO pokes (poked_by_user_id, poked_user_id, poked_at) VALUES ('$userId', '$pokedUserId', '$pokeDate')";

            if ($this->db->getConnection()->query($query)) {
                http_response_code(200);
                $this->sendEmail($pokedUserId, $userId);
            } else {
                http_response_code(500);
                echo 'Failed to add poke.';
            }
        } else {
            http_response_code(400);
            echo 'Bad request.';
        }
    }

    public function updatePokePopup()
    {
        $pokes = [];
        if (Session::isLoggedIn()) {
            $pokes = $this->pokeRepository->findPokesByUser(self::POKES_PER_PAGE, (int)$_SESSION['user_id']);
        }

        ob_start();
        include 'App/Views/components/pokes-popup.php';
        $tableContent = ob_get_clean();

        $responseData = [
            'tableContent' => $tableContent,
        ];

        header('Content-Type: application/json');
        echo json_encode($responseData);
    }

    public function searchPokes() 
    {

        $fromDate = $_GET['fromDate'] ?? '';
        $toDate = $_GET['toDate'] ?? '';
        $userName = isset($_GET['userName']) ? urldecode($_GET['userName']) : '';;
        
        $pokes = $this->pokeRepository->findPaginatedSearchedPokes($fromDate, $toDate, $userName, self::POKES_PER_PAGE, 0);

        ob_start();
        include 'App/Views/components/pokes-table.php';
        $tableContent = ob_get_clean();

        $currentPage = 1;
        $totalUsers = $this->pokeRepository->findSearchedPokeCount($fromDate, $toDate, $userName);
        $totalPages = ceil($totalUsers / self::POKES_PER_PAGE);
        $currentQuery = $this->getCurrentQuery($fromDate, $toDate, $userName);

        ob_start();
        include 'App/Views/components/pagination.php';
        $paginationContent = ob_get_clean();

        $data = $tableContent . $paginationContent;
        
        $responseData = [
            'data' => $data,
        ];

        header('Content-Type: application/json');
        echo json_encode($responseData);
    }

    public function sendEmail(int $pokedUserId, int $userId) {
        $pokedUserId = $this->userRepository->findOneById($pokedUserId);
        $user = $this->userRepository->findOneById($userId);

        $to = $pokedUserId->getEmail();
        $subject = "Naujas Poke!!!";
        $message = $user->getUserName() . " siunčia tau poke";

        $headers = "From: " . $user->getEmail() . "\r\n";
        // Galima siusti su HTML
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        mail($to, $subject, $message, $headers);
    }

    private function getCurrentQuery(string $fromDate, string $toDate, string $userName): string
    {
        return '?fromDate=' . $fromDate . '&toDate=' . $toDate . '&userName=' .$userName;
    }
}
