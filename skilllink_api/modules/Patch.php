<?php

class Patch extends Common{
    protected $pdo;

    public function __construct(\PDO $pdo){
    
       $this->pdo = $pdo; 
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
    
    public function patchUsers($body, $id){
        $user = getallheaders()['X-Auth-User'];
        $condition = "userId = :id";
        $tables = ['accounts_tbl'];
        $message = "User updated successfully.";


        try {   
            if(!$this->isUserIdExist($id, $this->pdo)){
                throw new Exception("User doesn't exist.");
            }

            if (isset($body->password)){
                $body->password = $this->encryptPassword($body->password);
            }
            return $this->patchData($tables, $body, $id, $condition, $message, $this->pdo);
        } catch(Exception $e) {
            $this->logger($user, "PATCH", $e->getMessage());
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }
       

    }

    public function archiveUsers($id){
        $user = getallheaders()['X-Auth-User'];
        $condition = "userId = :id";
        $tables = ['accounts_tbl', 'users_tbl'];
        $body = new stdClass();
        $body->isdeleted = 1;

        try {   
            if(!$this->isUserIdExist($id, $this->pdo)){
                throw new Exception("User doesn't exist.");
            }

            return $this->patchData($tables, $body, $id, $condition, "User archived successfully.", $this->pdo);
        } catch(Exception $e) {
            $this->logger($user, "PATCH", $e->getMessage());
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }
    }

    public function archiveOffers($id){
        $user = getallheaders()['X-Auth-User'];
        $condition = "offerId = :id";
        $tables = ["services_tbl"];
        $body = new stdClass();
        $body->isdeleted = 1;

        try {   
            if(!$this->isIdExist($tables[0], "offerId=$id AND isdeleted=0", $this->pdo)){
                throw new Exception("OfferId doesn't exist.");
            }

            return $this->patchData($tables, $body, $id, $condition, "Offer archived successfully.", $this->pdo);
        } catch(Exception $e) {
            $this->logger($user, "PATCH", $e->getMessage());
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }
    }

    public function archiveRequests($id){
        $user = getallheaders()['X-Auth-User'];
        $condition = "requestId=:id";
        $tables = ["requests_tbl"];
        $body = new stdClass();
        $body->isdeleted = 1;

        try {   
            if(!$this->isIdExist($tables[0], "requestId=$id AND isdeleted=0", $this->pdo)){
                throw new Exception("RequestId doesn't exist.");
            }

            return $this->patchData($tables, $body, $id, $condition, "Request archived successfully.", $this->pdo);
        } catch(Exception $e) {
            $this->logger($user, "PATCH", $e->getMessage());
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }

        
    }

    public function archiveMessages($id){
        $user = getallheaders()['X-Auth-User'];
        $condition = "messageId=:id";
        $tables = ["messages_tbl"];
        $body = new stdClass();
        $body->isdeleted = 1;

        try {
            if(!$this->isIdExist($tables[0], "messageId=$id AND isdeleted=0", $this->pdo)){
                throw new Exception("MessageId doesn't exist.");
            }

            return $this->patchData($tables, $body, $id, $condition, "Message archived successfully.", $this->pdo);
        } catch(Exception $e) {
            $this->logger($user, "PATCH", $e->getMessage());
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }

        
    }

    public function archiveReviews($id){
        $user = getallheaders()['X-Auth-User'];
        $condition = "reviewId=:id";
        $tables = ["reviews_tbl"];
        $body = new stdClass();
        $body->isdeleted = 1;

        try {  
            if(!$this->isIdExist($tables[0], "reviewId=$id AND isdeleted=0", $this->pdo)){
                throw new Exception("ReviewId doesn't exist.");
            }

            return $this->patchData($tables, $body, $id, $condition, "Reveiew archived successfully.", $this->pdo);
        } catch(Exception $e) {
            $this->logger($user, "PATCH", $e->getMessage());
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }

    }
    
    public function patchOffer($body, $id){
        $user = getallheaders()['X-Auth-User'];
        $condition = "offerId=:id AND isdeleted=0";
        $tables = ["services_tbl"];

        try {
            if(!$this->isIdExist($tables[0], "offerId=$id AND isdeleted=0", $this->pdo)){
                throw new Exception("OfferId doesn't exist.");
            }

            return $this->patchData($tables, $body, $id, $condition, "Offer updated successfully.", $this->pdo);
        } catch(Exception $e) {
            $this->logger($user, "PATCH", $e->getMessage());
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }
        
    }

    public function patchRequest($body, $id){
        $user = getallheaders()['X-Auth-User'];
        $condition = "requestId=:id AND isdeleted=0";
        $tables = ["requests_tbl"];

        try {
            if(!$this->isIdExist($tables[0], "requestId=$id AND isdeleted=0", $this->pdo)){
                throw new Exception("RequestId doesn't exist.");
            }

            return $this->patchData($tables, $body, $id, $condition, "Request updated successfully.", $this->pdo);
        } catch(Exception $e) {
            $this->logger($user, "PATCH", $e->getMessage());
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }

        
    }
    
    public function patchTransaction($body, $id){
        $user = getallheaders()['X-Auth-User'];
        $condition = "id=:id";
        $tables = ["transactions_tbl"];

        try {
            if(!$this->isIdExist($tables[0], "id=$id", $this->pdo)){
                throw new Exception("Id doesn't exist.");
            }

            return $this->patchData($tables, $body, $id, $condition, "Transaction updated successfully.", $this->pdo);
        } catch(Exception $e) {
            $this->logger($user, "PATCH", $e->getMessage());
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }

        
    }

    public function patchBookings($body, $id){
        $user = getallheaders()['X-Auth-User'];
        $condition = "bookingId=:id AND iscancelled=0";
        $tables = ["bookings_tbl"];

        try {
            if(!$this->isIdExist($tables[0], "bookingId=$id AND iscancelled=0", $this->pdo)){
                throw new Exception("BookingId doesn't exist.");
            }

            return $this->patchData($tables, $body, $id, $condition, "Booking updated successfully.", $this->pdo);
        } catch(Exception $e) {
            $this->logger($user, "PATCH", $e->getMessage());
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }

    }

    public function cancelBooking($id){
        $user = getallheaders()['X-Auth-User'];
        $condition = "bookingId=:id";
        $tables = ["bookings_tbl"];
        $body = new stdClass();
        $body->cancellation_date = date_create()->format('Y-m-d H:i:s');
        $body->iscancelled = 1;
        
        try {
            if(!$this->isIdExist($tables[0], "bookingId=$id", $this->pdo)){
                throw new Exception("BookingId doesn't exist.");
            }

            return $this->patchData($tables, $body, $id, $condition, "Booking cancelled successfully.", $this->pdo);
        } catch(Exception $e) {
            $this->logger($user, "PATCH", $e->getMessage());
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }
        
    }
    
    public function Deposit($body, $id){
        $condition = "userId = :id";
        $tables = ["balance_tbl"];
        $message = "Successfully deposited.";

        try{
            $user = getallheaders()['X-Auth-User'];

            if(!isset($body->balance)){
                throw new Exception("Insufficient parameters.");
            }

            if(!$this->isIdExist($tables[0], "userId=$id", $this->pdo)){
                throw new Exception("User doesn't exist.");
            } 

            $amount = $body->balance;
        
            $this->createTransaction($id, $amount, "deposit", $this->pdo);
            return $this->addBalance($tables, $body, $id, $condition, $message, $this->pdo);
        }catch(Exception $e){
            $this->logger($user, "PATCH", $e->getMessage());
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }

    }
    
    public function Withdraw($body, $id){
        $condition = "userId = :id";
        $tables = ["balance_tbl"];
        $message = "Successfully withdrawn.";

        try {
            $user = getallheaders()['X-Auth-User'];

            if(!isset($body->balance)){
                throw new Exception("Insufficient parameters.");
            }

            if(!$this->isIdExist($tables[0], "userId=$id", $this->pdo)){
                throw new Exception("User doesn't exist.");
            } 
            
            $amount = $body->balance;

            $this->createTransaction($id, -$amount, "withdrawal", $this->pdo);
            return $this->deductBalance($tables, $body, $id, $condition, $message, $this->pdo);
        } catch(Exception $e) {
            $this->logger($user, "PATCH", $e->getMessage());
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }
    }

    public function Transfer($body){
        $user = getallheaders()['X-Auth-User'];
        $tables = ["balance_tbl"];
        try {
            if(!isset($body->from_user_id) || !isset($body->to_user_id) || !isset($body->amount)){
                throw new Exception("Insufficient parameters.");
            }

            if(!$this->isIdExist($tables[0], "userId=$body->from_user_id", $this->pdo) || !$this->isIdExist($tables[0], "userId=$body->to_user_id", $this->pdo)){
                throw new Exception("User doesn't exist.");
            } 
            
            return $this->transferFunds($body, $this->pdo);
        } catch(Exception $e) {
            $this->logger($user, "PATCH", $e->getMessage());
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }
        
    }
        
    public function patchMessages($body, $id){
        $user = getallheaders()['X-Auth-User'];
        $condition = "messageId=:id AND isdeleted=0";
        $tables = ["messages_tbl"];

        try {
            if(!$this->isIdExist($tables[0], "messageId=$id AND isdeleted=0", $this->pdo)){
                throw new Exception("MessageId doesn't exist.");
            }

            return $this->patchData($tables, $body, $id, $condition, "Message updated successfully.", $this->pdo);
        } catch(Exception $e) {
            $this->logger($user, "PATCH", $e->getMessage());
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }
        
    }

    public function patchReviews($body, $id){
        $user = getallheaders()['X-Auth-User'];
        $condition = "reviewId=:id AND isdeleted=0";
        $tables = ["reviews_tbl"];

        try {
            if(!$this->isIdExist($tables[0], "reviewId=$id AND isdeleted=0", $this->pdo)){
                throw new Exception("ReviewId doesn't exist.");
            }

            return $this->patchData($tables, $body, $id, $condition, "Review updated successfully.", $this->pdo);
        } catch(Exception $e) {
            $this->logger($user, "PATCH", $e->getMessage());
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }
        
    }
}

?>