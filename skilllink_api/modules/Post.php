<?php

include_once "./modules/Common.php";

class Post extends Common{
    protected $pdo;

    public function __construct(\PDO $pdo){
       $this->pdo = $pdo; 
    }

    public function postServices($body){
        $user = getallheaders()['X-Auth-User'];
        try {
            $user = $this->getUserIdByUsername($user, $this->pdo);
            $body->providerUserId = $body->providerUserId ?? $user;
            if(!isset($body->providerUserId) || !isset($body->offerTitle) || !isset($body->description) || !isset($body->price)){
                throw new Exception("Insufficient parameters.");
            }

            if(!$this->isUserIdExist($body->providerUserId, $this->pdo)){
                throw new Exception("Provider User doesn't exist.");
            }
            return $this->postData("services_tbl", $body, "Successfully created an offer.", $this->pdo);
        } catch(Exception $e) {
            $this->logger($user, "POST", $e->getMessage());
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }
        
    }
    
    
    public function postRequest($body){
        $user = getallheaders()['X-Auth-User'];
        try{
            $user = $this->getUserIdByUsername($user, $this->pdo);
            $body->seekerUserId = $body->seekerUserId ?? $user;
            if(!isset($body->seekerUserId) || !isset($body->requestTitle) || !isset($body->description) || !isset($body->price)){
                throw new Exception("Insufficient parameters.");
            }
    
            if(!$this->isUserIdExist($body->seekerUserId, $this->pdo)){
                throw new Exception("Seeker User doesn't exist.");
            }
            return $this->postData("requests_tbl", $body,  "Successfully created a request.", $this->pdo);
        } catch(Exception $e) {
            $this->logger($user, "POST", $e->getMessage());
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }
        
    }

    public function postTransactions($body){
        $user = getallheaders()['X-Auth-User'];
        try{
            if(!isset($body->user_id) || !isset($body->amount) || !isset($body->type) || !isset($body->from_user_id)){
                throw new Exception("Insufficient parameters.");
            }
            if(!$this->isUserIdExist($body->user_id, $this->pdo) || !$this->isUserIdExist($body->from_user_id, $this->pdo)){
                throw new Exception("User doesn't exist.");
            }
            return $this->postData("transactions_tbl", $body,  "Successfully created a transaction.", $this->pdo);
        } catch(Exception $e) {
            $this->logger($user, "POST", $e->getMessage());
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }
        
    }

    public function postBookings($body){
        $user = getallheaders()['X-Auth-User'];
        try{
            $user = $this->getUserIdByUsername($user, $this->pdo);
            $body->userId = $body->userId ?? $user;

            if(!isset($body->userId) || !isset($body->offerId) || !isset($body->booking_status) || !isset($body->payment_status) || !isset($body->payment_type) || !isset($body->total_amount) || !isset($body->staffId) || !isset($body->location)){
                throw new Exception("Insufficient parameters.");
            }

            $condition = "offerId=$body->offerId";
            if(!$this->isIdExist("services_tbl",$condition, $this->pdo)){
                throw new Exception("Offer doesn't exist.");
            }

            if(!$this->isUserIdExist($body->userId, $this->pdo) || !$this->isUserIdExist($body->staffId, $this->pdo)){
                throw new Exception("User doesn't exist.");
            }
            return $this->postData("bookings_tbl", $body,  "Successfully added to the bookings.", $this->pdo);
        } catch(Exception $e){
            $this->logger($user, "POST", $e->getMessage());
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }
        
    }
    
    public function postMessages($body){
        $user = getallheaders()['X-Auth-User'];
        try{
            $user = $this->getUserIdByUsername($user, $this->pdo);
            $body->fromUserId = $body->fromUserId ?? $user;
            if(!isset($body->fromUserId) || !isset($body->toUserId)){
                throw new Exception("Insufficient parameters.");
            }
            if(!$this->isUserIdExist($body->fromUserId, $this->pdo) || !$this->isUserIdExist($body->toUserId, $this->pdo)){
                throw new Exception("User doesn't exist.");
            }
            return $this->postData("messages_tbl", $body,  "Successfully sent a message.", $this->pdo);
        } catch(Exception $e) {
            $this->logger($user, "POST", $e->getMessage());
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }
    }

    public function postReviews($body){
        $user = getallheaders()['X-Auth-User'];
        try{
            $user = $this->getUserIdByUsername($user, $this->pdo);
            $body->fromUserId = $body->fromUserId ?? $user;
            if(!isset($body->fromUserId) || !isset($body->toUserId)){
                throw new Exception("Insufficient parameters.");
            }

            if(!$this->isUserIdExist($body->fromUserId, $this->pdo) || !$this->isUserIdExist($body->toUserId, $this->pdo)){
                throw new Exception("User doesn't exist.");
            }
            return $this->postData("reviews_tbl", $body,  "Successfully added a review.", $this->pdo);
        } catch(Exception $e) {
            $this->logger($user, "POST", $e->getMessage());
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }
    }

}

?>