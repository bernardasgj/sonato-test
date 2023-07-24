<?php

namespace App\Controllers;

use App\Repository\UserRepository;
use Database;
use Session;

class UserController extends \Controller 
{
    private $db;
    private UserRepository $userRepository;
    const USERS_PER_PAGE = 8;

    public function __construct()
    {
        $this->db = new Database();
        $this->userRepository = new UserRepository($this->db);
    }

    public function index(): void
    {
        if (!Session::isLoggedIn()) {
            $this->redirect('/login');
        }

        $userName = isset($_GET['q']) ?  $_GET['q'] : '';

        if ($userName) {
            $totalUsers = $this->userRepository->countSearchResults($userName);
        } else {
            
            $totalUsers = $this->userRepository->countAll();
        }
        $currentQuery = '?q=' . $userName;
        
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $totalPages = ceil($totalUsers / self::USERS_PER_PAGE);

        if ($currentPage > $totalPages && $currentPage !== 1) {
            $currentPage = $totalPages;
        }

        if ($userName) {
            $users = $this->userRepository->searchUsers($userName, self::USERS_PER_PAGE, self::USERS_PER_PAGE * ($currentPage - 1));
        } else {
            $users = $this->userRepository->findBy(self::USERS_PER_PAGE, self::USERS_PER_PAGE * ($currentPage - 1));
        }

        $this->render('pages/user/index', [
            'users'        => $users,
            'totalPages'   => $totalPages,
            'currentPage'  => $currentPage,
            'currentQuery' => $currentQuery,
            'userName'     => $userName,
        ]);
    }

    public function searchUser() 
    {
        $searchTerm = isset($_GET['q']) ?  $_GET['q'] : '';

        $users = $this->userRepository->searchUsers($searchTerm, self::USERS_PER_PAGE, 0);


        ob_start();
        include 'App/Views/components/user-table.php';
        $tableContent = ob_get_clean();

        $currentPage = 1;
        $totalUsers = $this->userRepository->countSearchResults($searchTerm);
        $totalPages = ceil($totalUsers / self::USERS_PER_PAGE);
        $currentQuery = '?q=' . $searchTerm;

        ob_start();
        include 'App/Views/components/pagination.php';
        $paginationContent = ob_get_clean();

        $data = $tableContent . $paginationContent;

        $responseData = [
            'data'      => $data,
        ];

        header('Content-Type: application/json');
        echo json_encode($responseData);
    }

    public function updateAccount(): void
    {
        if (!Session::isLoggedIn()) {
            $this->redirect('/');
        }
        
        $errors = [];

        $currentUser = $this->userRepository->findOneById($_SESSION['user_id']);
        $inputData = [
            'username' => $currentUser->getUsername(),
            'first_name' => $currentUser->getFirstName(),
            'last_name' => $currentUser->getLastName(),
            'email' => $currentUser->getEmail(),
        ];

        if (isset($_POST['update_user'])) {
            $username = mysqli_real_escape_string($this->db->getConnection(), $_POST['username']);
            $firstName = mysqli_real_escape_string($this->db->getConnection(), $_POST['first_name']);
            $lastName = mysqli_real_escape_string($this->db->getConnection(), $_POST['last_name']);
            $email = mysqli_real_escape_string($this->db->getConnection(), $_POST['email']);
            $password_1 = mysqli_real_escape_string($this->db->getConnection(), $_POST['password_1']);
            $password_2 = mysqli_real_escape_string($this->db->getConnection(), $_POST['password_2']);
    
            $inputData = [
                'username'   => $currentUser->getUsername(),
                'first_name' => $firstName,
                'last_name'  => $lastName,
                'email'      => $email,
                'password_1' => $password_1,
                'password_2' => $password_2,
            ];
    
            if ($currentUser->getUsername() !== $username) { $errors['username'] = "Rimtai?"; }
            if (empty($firstName)) { $errors['first_name'] = "Vardas turi būti įvestas"; }
            if (empty($lastName)) { $errors['last_name'] = "Pavardė turi būti įvesta"; }
            if (empty($email)) { 
                $errors['email'] = "El. paštas turi būti įvestas"; 
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "El. paštas turi būti validus"; 
            }
            if (empty($password_1)) { 
                $errors['password_1'] = "Slaptažodis turi būti įvestas"; 
            } else if (!$this->isPasswordValid($password_1)) {
                $errors['password_1'] = "Slaptažodis turi turėti bent vieną didžiąją raidę ir skaičių";
            }
            if ($password_1 != $password_2) {
                $errors['password_2'] = "Slaptažodžio pakartojimas turi sutapti";
            }
    
            $user_id = $currentUser->getId();
    
            if (count($errors) == 0) {
                $password = md5($password_1);
    
                $update_query = "UPDATE users 
                                 SET first_name='$firstName', last_name='$lastName', email='$email', password='$password' 
                                 WHERE id='$user_id'";
                mysqli_query($this->db->getConnection(), $update_query);
                $_SESSION['success'] = "Duomenys atnaujinti sekmingai";

                $this->redirect('/');
            }

            $_SESSION['error'] = "Formoje yra klaidu";

        }
  
        $this->render('pages/user/update-account', ['errors' => $errors, 'inputData' => $inputData]);
    }

    public function login(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirect('/');
        }
        
        $errors = [];

        if (isset($_POST['login_user'])) {
            $username = mysqli_real_escape_string($this->db->getConnection(), $_POST['username']);
            $password = mysqli_real_escape_string($this->db->getConnection(), $_POST['password']);

            if (count($errors) == 0) {
        
                $password = md5($password);
                $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
                $results = mysqli_query($this->db->getConnection(), $query);
        
                if (mysqli_num_rows($results) == 1) {
                    $row = mysqli_fetch_assoc($results);
                    $_SESSION['user_id'] = $row['id']; 
                    $_SESSION['info'] = "Sveikas sugrizes " . $row['username'];

                    $this->redirect('/');
                } else {
                    $_SESSION['error'] = "Blogi prisijungimo duomenys.";
                }
            }
        }
  
        $this->render('pages/user/login');
    }

    public function register(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirect('/');
        }
    
        $errors = [];
        $inputData = [];
    
        /**
         * Butu gerai validacija daryti per Validator, bet at some point reikia scope riboti
         */
        if (isset($_POST['reg_user'])) {
            $username = mysqli_real_escape_string($this->db->getConnection(), $_POST['username']);
            $firstName = mysqli_real_escape_string($this->db->getConnection(), $_POST['first_name']);
            $lastName = mysqli_real_escape_string($this->db->getConnection(), $_POST['last_name']);
            $email = mysqli_real_escape_string($this->db->getConnection(), $_POST['email']);
            $password_1 = mysqli_real_escape_string($this->db->getConnection(), $_POST['password_1']);
            $password_2 = mysqli_real_escape_string($this->db->getConnection(), $_POST['password_2']);

            $inputData = [
                'username'   => $username,
                'first_name' => $firstName,
                'last_name'  => $lastName,
                'email'      => $email,
                'password_1' => $password_1,
                'password_2' => $password_2,
            ];

               
            $user_check_query = "SELECT * FROM users WHERE username='$username' LIMIT 1";
            $result = mysqli_query($this->db->getConnection(), $user_check_query);
            $user = mysqli_fetch_assoc($result);
            
            if ($user) {
                $errors['username'] = "Vartotojo vardas jau užimtas";
            }

            if (empty($username)) { $errors['username'] = "Prisijungimo turi būti įvestas"; }
            if ($this->isUsernameValid($username)) { $errors['username'] = "Prisijungimo varde yra neleidziamu simboliu"; }
            if (empty($firstName)) { $errors['first_name'] = "Vardas turi būti įvestas"; }
            if (empty($lastName)) { $errors['last_name'] = "Pavardė turi būti įvesta"; }
            if (empty($email)) { 
                $errors['email'] = "El. paštas turi būti įvestas"; 
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "El. paštas turi būti validus"; 
            }
            if (empty($password_1)) { 
                $errors['password_1'] = "Slaptažodis turi būti įvestas"; 
            } else if (!$this->isPasswordValid($password_1)) {
                $errors['password_1'] = "Slaptažodis turi turėti bent vieną didžiąją raidę ir skaičių";
            }

            if ($password_1 != $password_2) {
                $errors['password_2'] = "Slaptažodžio pakartojimas turi sutapti";
            }
    
            if (count($errors) == 0) {
                $password = md5($password_1);
    
                $query = "INSERT INTO users (username, first_name, last_name, email, password) 
                        VALUES('$username', '$firstName', '$lastName', '$email', '$password')";
                mysqli_query($this->db->getConnection(), $query);
                $this->redirect('/login');
            }

            $_SESSION['error'] = "Forma nera validi";
        }
        
        $this->render('pages/user/register', ['errors' => $errors, 'inputData' => $inputData]);
    }


    public function logout(): void
    {
        if (!Session::isLoggedIn()) {
            $this->redirect('/login');
        }

        unset($_SESSION['user_id']);

        $_SESSION['success'] = "Atsijungete sekmingai";

        $this->redirect('/login');
    }

    private function isPasswordValid(string $password): bool
    {
        if (!preg_match('/[A-Z]/', $password) || !preg_match('/\d/', $password)) {
            return false;
        }
    
        return true;
    }

    /**
     * Del tokiu atveju reiktu validato, nes labai panasus regex per du failus - asking for trouble
     */
    private function isUsernameValid($username) {
        $pattern = '/^[^&=_\-\'+,<>]*\.[^&=_\-\'+,<>]*$/';
    
        if (preg_match($pattern, $username)) {
            return true;
        } else {
            return false;
        }
    }
    
}
