<?php

include_once "./modules/Common.php";

class Authentication extends Common{
    protected $pdo;

    public function __construct(\PDO $pdo){
       $this->pdo = $pdo; 
    }

    public function isAdmin(){
        $headers = array_change_key_case(getallheaders(),CASE_LOWER);
        $id = $this->getUserIdByUsername($headers['x-auth-user'],$this->pdo);

        return $this->getRole($id,$this->pdo) === "admin";
    }

    public function isAuthorized(){
        $headers = array_change_key_case(getallheaders(),CASE_LOWER);

        if (!isset($headers['authorization']) || !isset($headers['x-auth-user'])) {
            return false;
        }
        
        $providedToken = $headers['authorization'];
        $expectedToken = $this->getToken();
    
        return hash_equals($expectedToken, $providedToken);
    }

    private function getToken(){
        $headers = array_change_key_case(getallheaders(),CASE_LOWER);

        $sqlString = "SELECT token FROM accounts_tbl WHERE username = ?";
        $stmt = $this->pdo->prepare($sqlString);
        $stmt->execute([$headers['x-auth-user']]);
        $result = $stmt->fetchAll()[0];

        return $result['token'];
    }

    #change app and dev
    private function generateHeader(){
        $header = [ 
            "typ" => "JWT",
            "alg" => "HS256",
            "app" => "SkillLink",
            "dev" => "Richard and Mac."
        ];

        return base64_encode(json_encode($header));

    }

    private function generatePayload($id, $username){
        $payload = [ 
            "uid" => $id,
            "uc" => $username,
            "email" => "test@test.com",
            "date" => date_create(),
            "exp" => date("Y-m-d H:i:s")
        ];

        return base64_encode(json_encode($payload));
    }

    private function generateToken($id, $username){
        $header = $this->generateHeader();
        $payload = $this->generatePayload($id, $username);
        $signature = hash_hmac("sha256", "$header.$payload", TOKEN_KEY);
        return "$header.$payload." . base64_encode($signature);
    }

    private function isSamePassword($inputPassword, $existingHash){
        $hash = crypt($inputPassword, $existingHash);
        return $hash === $existingHash;
    }

    private function encryptPassword($password){
        $hashFormat = "$2y$10$";
        $saltLength = 22;
        $salt = $this->generateSalt($saltLength);
        return crypt($password, $hashFormat . $salt);
    }

    private function generateSalt($length){
        $urs = md5(uniqid(mt_rand(), true));
        $b64String = base64_encode(($urs));
        $mb64String = str_replace("+", ".", $b64String);

        return substr($mb64String, 0, $length);
    }

    public function saveToken($token , $username){
        $errmsg = "";
        $code = 0;

        try {
            $sqlString = "UPDATE accounts_tbl SET token=? WHERE username = ?";
            $sql = $this->pdo->prepare($sqlString);
            $sql->execute([$token , $username]);

            $code = 200;
            $data = null;

            return array("data"=>$data, "code"=>$code);
        } catch(\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }

        return array("errmsg"=>$errmsg, "code"=>$code);
    }

    public function login($body){
        
        $code = 0;
        $payload = "";
        $remarks = "";
        $message = "";

        try {
            
            if(!isset($body->username) || !isset($body->password)){
                throw new Exception("Insufficient parameters.");
            }

            $username = $body->username;
            $password = $body->password;

            $sqlString = "SELECT userId, username, password, token FROM accounts_tbl WHERE username=?";
            $stmt = $this->pdo->prepare($sqlString);
            $stmt->execute([$username]);


            if($stmt->rowCount() > 0){
                $result = $stmt->fetchAll()[0];

                if($this->isSamePassword($password,$result['password'])){
                    $code = 200;
                    $remarks = "Success.";
                    $message = "Logged in successfully.";

                    $token = $this->generateToken($result['userId'],$result['username']);
                    $token_arr = explode('.', $token);
                    $this->saveToken($token_arr[2], $result['username']);

                    $payload = array("userId"=>$result['userId'], "username"=>$result['username'], "token"=>$token_arr[2]);
                } else {
                    $code = 401;
                    $payload = null;
                    $remarks = "Failed.";
                    $message = "Invalid Password.";
                }
            } else {
                $code = 401;
                $payload = null;
                $remarks = "Failed.";
                $message = "Username Not Found.";
            }
        } 
        catch(\PDOException $e) {
            $message = $e->getMessage();
            $remarks = "Failed.";
            $code = 400;
        } catch(Exception $e) {
            $message = $e->getMessage();
            $remarks = "Failed.";
            $code = 400;
        }
        http_response_code($code);
        return $this->generateResponse($payload, $remarks, $message, $code);
    }


    public function addAccount($body){
        $errmsg = "";
        $code = 0;

        try {
            echo "Username set? " . (isset($body->username) ? 'Yes' : 'No') . "\n";
            if(!isset($body->username) || !isset($body->password) || !isset($body->email) || !isset($body->firstname) || !isset($body->lastname) || !isset($body->roles)){
                throw new Exception("Insufficient parameters.");
            }
            $body->password = $this->encryptPassword($body->password);

            $accountValues = [$body->username, $body->password];
            $sqlString = "INSERT INTO accounts_tbl(username, password) VALUES (?,?)";
            $sql = $this->pdo->prepare($sqlString);
            $sql->execute($accountValues);

            $userId = $this->pdo->lastInsertId();

            $userValues = [$userId, $body->email, $body->firstname, $body->lastname, $body->roles];
            $sqlString1 = "INSERT INTO users_tbl(userId, email, firstname, lastname, roles) VALUES (?,?,?,?,?)";
            $sql1 = $this->pdo->prepare($sqlString1);
            $sql1->execute($userValues);

            $code = 200;
          
            if($code == 200){
                http_response_code($code);
                return $this->generateResponse(null, "Success", "Successfully created account.", $code);
            }

        } catch(\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        } catch(Exception $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }
        http_response_code($code);
        return $this->generateResponse(null, "Failed", $errmsg, $code);
    }
}

?>