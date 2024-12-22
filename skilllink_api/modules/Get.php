<?php

include_once "./modules/Common.php";

class Get extends Common{

    protected $pdo;

    public function __construct(\PDO $pdo){
        $this->pdo = $pdo;
    }
    
    public function getLogs($date = null){
        if ($date == null){
            $date = date("Y-m-d");
        }
        
        $filename = "./logs/". $date . ".log";

        $logs = array();
        try{
            $file = new SplFileObject($filename);
            while(!$file->eof()){
                array_push($logs, $file->fgets());
            }
            $remarks = "Success";
            $message = "Successfully retrieved logs.";
        }
        catch(Exception $e){
            $remarks = "Failed";
            $message = $e->getMessage();
        }

        return $this->generateResponse(array("logs"=>$logs), $remarks, $message, 200);
    }

    public function getUsers($id = null){ 
        $condition = "isdeleted=0"; 
        if($id != null){
            $condition .= " AND userID=" . $id; 
        }
        
        return $this->getDataByTable('users_tbl', $condition, "Successfully retrieved user records.", $this->pdo);
    }

    public function getOffers($id = null){
        $condition = "isdeleted=0";
        if($id != null){
            $condition .= " AND offerId=" . $id; 
        }

        return $this->getDataByTable('services_tbl', $condition, "Successfully retrieved services records.", $this->pdo);
    }

    public function getRequests($id = null){
        $condition = "isdeleted=0";
        if($id != null){
            $condition .= " AND requestId=" . $id; 
        }

        return $this->getDataByTable('requests_tbl', $condition, "Successfully retrieved requests records.", $this->pdo);
    }

    public function getTransactions($id = null){
        $condition = null;
        if($id != null){
            $condition = "id=" . $id;
        }

        return $this->getDataByTable('transactions_tbl', $condition, "Successfully retrieved transaction records.", $this->pdo);
    }

    public function getBalance($id = null){
        $condition = null;
        if($id != null){
            $condition = "userId=" . $id;
        }

        return $this->getDataByTable('balance_tbl', $condition, "Successfully retrieved balance records.", $this->pdo);
    }

    public function getBookings($id = null){
        $condition = "iscancelled=0";
        if($id != null){
            $condition .= " AND bookingId=" . $id;
        }

        return $this->getDataByTable('bookings_tbl', $condition, "Successfully retrieved booking records.", $this->pdo);
    }

    public function getMessages($id = null){
        $condition = "isdeleted=0";
        if($id != null){
            $condition .= " AND messageId=" . $id;
        }

        return $this->getDataByTable('messages_tbl', $condition, "Successfully retrieved message records.", $this->pdo);
    }

    public function getReviews($id = null){
        $condition = "isdeleted=0";
        if($id != null){
            $condition .= " AND reviewId=" . $id;
        }

        return $this->getDataByTable('reviews_tbl', $condition, "Successfully retrieved review records.", $this->pdo);
    }

    public function getUserReviews($id = null){
        $condition = "isdeleted=0";
        
        if($id != null){
            if ($this->getRole($id, $this->pdo) == "seeker"){
                $condition .= " AND fromUserId=" . $id;
            } elseif ($this->getRole($id, $this->pdo) == "provider"){
                $condition .= " AND toUserId=" . $id;
            }
        }

        return $this->getDataByTable('reviews_tbl', $condition, "Successfully retrieved review records.", $this->pdo);
    }

    public function getUserBookings($id = null){
        $condition = "iscancelled=0";
        if($id != null){
            $condition .= " AND userId=" . $id;
        }

        return $this->getDataByTable('bookings_tbl', $condition, "Successfully retrieved booking records.", $this->pdo);
    }

    public function getUserTransactions($id = null){
        $condition = null;
        if($id != null){
            $condition = "user_id=" . $id;
        }

        return $this->getDataByTable('transactions_tbl', $condition, "Successfully retrieved transaction records.", $this->pdo);
    }

    public function getUserMessages($type,$id = null){
        $condition = "isdeleted=0";
        if($id != null){
            if ($type == "sent"){
                $condition .= " AND fromUserId=" . $id;
            } elseif ($type == "received") {
                $condition .= " AND toUserId=" . $id;
            }
           
        }

        return $this->getDataByTable('messages_tbl', $condition, "Successfully retrieved message records.", $this->pdo);
    }
}

?>