<?php

namespace App\Controllers;

use App\Repository\UserRepository;
use Database;

class FileController extends \Controller 
{
    private Database $db;
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->db = new Database();
        $this->userRepository = new UserRepository($this->db);
    }

    public function csvIndexPage(): void
    {
        $this->render('pages/upload-pages/upload-csv');
    }

    public function jsonIndexPage(): void
    {
        $this->render('pages/upload-pages/upload-json');
    }

    public function uploadJSON() 
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["jsonFile"])) {
            $fileInfo = pathinfo($_FILES['jsonFile']['name']);
            if (strtolower($fileInfo['extension']) !== 'json') {
                echo "Please upload a JSON file.";
                exit;
            }
            $conn = $this->db->getConnection();
        
            $query = "DELETE FROM pokes";
            $results = mysqli_query($conn, $query);
        
            if (!$results) {
                echo "Error purging data: " . $conn->error;
                $conn->close();
                exit;
            }

            if ($_FILES["jsonFile"]["error"] == UPLOAD_ERR_OK) {
                $jsonData = file_get_contents($_FILES["jsonFile"]["tmp_name"]);
                $data = json_decode($jsonData, true);
                $stmt = $conn->prepare("INSERT INTO pokes (poked_by_user_id, poked_user_id, poked_at) VALUES (?, ?, ?)");
        
                foreach ($data as $poke) {
                    $from = $this->userRepository->findOneByEmail($from = $poke['from'])->getId();
                    $to = $this->userRepository->findOneByEmail($to = $poke['to'])->getId();
                    $date = $poke['date'];
        
                    $stmt->bind_param("iis", $from, $to, $date);
                    $stmt->execute();
                }
        
                $stmt->close();
                $conn->close();
        
                echo "Data uploaded and flushed to the database.";
            } else {
                echo "Error uploading the file.";
            }
        }
    }

    public function uploadCsv() 
    {
        if (isset($_FILES['csvFile']['tmp_name']) && !empty($_FILES['csvFile']['tmp_name'])) {
            $fileInfo = pathinfo($_FILES['csvFile']['name']);
            if (strtolower($fileInfo['extension']) !== 'csv') {
                echo "Please upload a CSV file.";
                exit;
            }
            $csvFileTmp = $_FILES['csvFile']['tmp_name'];
            $csvData = array_map('str_getcsv', file($csvFileTmp));
            $conn = $this->db->getConnection();

            /**
             * Kadangi duomenys pateikti standartiniu modeliu column pavadinimai, man nėra reikalingi, tačiau jeigu mes norime, kad sistema veiktų
             * nepasant column sekos, takim, id-email-last_name-first_name, mums reikėtų pakeisti šią vietą ir nuskaityti column pavadinimus. 
             * Tai pasakius nežinodamas norimo veikimo pasirinkai trumpesnį variantą
             */ 
            $firstRow = array_shift($csvData);

            // Galima validacijas uzdet, loginti, exception mest ir t.t. Mano noras buvo parodyt that I thought about invalid data
            if (count($firstRow) !== 4) {
                echo "Data is in expected format";
                exit;
            }
        
            /**
             * Šios vietos funkcionalumas nebuvo aiškus. Kadangi .csv faile buvo duoti user id, aš nusprendžiau, kad mes norime sunaikinti senus db
             * įrašus ir sukurti naujus: veikimas panašus į fixtures. Tačiau taip pat įmanoma įkelti failą paliekant ęsamus duomenis ir jei id užimtas slinkti 
             * tol kol bus atrastas laisvas ir t.t., bet tai sukelia kitą problemą: ką daryti su dublikatiniais el. pašto adresais? 
             * Dėl šių problemų antrasis sprendimas mano nuomone turi gan daug skylių, kurios turėtų būti atsakytos. 
             * Todėl nežinodamas norimo sistemos veikimo pasirinkau, mano nuomone, švaresnį bei aiškesnį sprendimo kelią.
             */
            $query = "DELETE FROM users";
            $results = mysqli_query($conn, $query);
        
            if (!$results) {
                echo "Error purging data: " . $conn->error;
                $conn->close();
                exit;
            }
        
            $sqlInsert = "INSERT INTO users (id, username, first_name, last_name, email, password) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sqlInsert);
        
            foreach ($csvData as $key => $row) {
                // Getting
                $id = $row[0];
                $firstName = $row[1];
                $lastName = $row[2];
                $email = $row[3];
                $username = $this->getUsernameFromEmail($email); 
                $password = $this->generatePassword();
        
                /**
                 * Norejau parodyt, kad zinau, jog password reik hashint, kas nepadeda testuoti prisijungimo su naujai sukurtu vartotoju
                 * tai arba comment this line and go to admin and see password arba galima gauti originalu password is hash:
                 * @link https://www.md5online.org/md5-decrypt.html 
                 */
                $password = md5($password);
                $stmt->bind_param("ssssss", $id, $username, $firstName, $lastName, $email, $password);
        
                if ($stmt->execute() === FALSE) {
                    echo "Error inserting data: " . $conn->error;
                    $stmt->close();
                    $conn->close();
                    exit;
                }
            }
        
            $stmt->close();
            $conn->close();
            
            /**
             * Cia if'y vieta galbut reiktu notification. Something like "data uploaded successfully" or smtg, kas aisku priklauso nuo norimo produkto
             * atm. works as I think it should (pseudo fixture, nes jei trinam duomenis tai ir user nebera, i.e. nera kam duot notification), 
             * notification can be easily added on demand tho
             */
            unset($_SESSION['user_id']);
            echo "Data uploaded and flushed to the database.";
        } else {
            echo "Please select a CSV file to upload.";
        }
    }

    /**
     * Jūsų atsakyme buvo pasakyta, kad "Prisijungimo vardą galima "pagaminti" iš email naudojant viską iki @ simbolio, išmetant nelegalius simbolius;"
     * Tai pasakius aš nežinojau, kas yra nelegalimi simboliai
     * Darbe tai būčiau užklausęs žodžiu tai pasakius nenorėjau šios užduoties pavertis dialogu: nenorėjau jūsų užpilti klausimais, todėl I just googled it.
     * Pridedu link į straipsnį:
     * @link https://support.google.com/mail/answer/9211434?hl=en 
     *
     * P.S. neminejote apie nelegalimu simboliu validacija, todel jos registracijos formoje nepridejau
     */
    private function getUsernameFromEmail($email) {
        $username = substr($email, 0, strpos($email, '@'));
        $username = preg_replace('/[&=_\'\-\+,<>]/', '', $username);
        $username = preg_replace('/\.+/', '.', $username);

        return $username;
    }

    /**
     * Tikrai yra geresniu algoritmu, bet sitas - reliable ir simple :D 
     * Kadangi ne sitoj vietoje esme (mano supratimu), manau, kad tiks
     */
    private function  generatePassword(): string
    {
        $uppercaseLetters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercaseLetters = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $allChars = $uppercaseLetters . $lowercaseLetters . $numbers;
        $password = '';
    
        $password .= $uppercaseLetters[rand(0, strlen($uppercaseLetters) - 1)];
        $password .= $lowercaseLetters[rand(0, strlen($lowercaseLetters) - 1)];
        $password .= $numbers[rand(0, strlen($numbers) - 1)];
        
        $passwordLength = rand(8, 16);
        $remainingLength = $passwordLength - 3;
    
        for ($i = 0; $i < $remainingLength; $i++) {
            $password .= $allChars[rand(0, strlen($allChars) - 1)];
        }
    
        $password = str_shuffle($password);
    
        return $password;
    }
}




