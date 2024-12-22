<?php
class Common{

    protected function logger($user, $method, $action){
        $filename = date("Y-m-d") . ".log";
        $datetime = date("Y-m-d H:i:s");
        $logMessage = "$datetime,$method,$user,$action" . PHP_EOL;
        error_log($logMessage, 3, "./logs/$filename");
    }
    
    private function generateInsertString($tablename, $body){
        $keys = array_keys($body);
        
        $fields = implode(",",$keys);

        $parameter_array = array_fill(0, count($body), "?");

        $parameters = implode(',', $parameter_array);

        $sql = "INSERT INTO $tablename($fields) VALUES ($parameters)";

        return $sql;
    }

    protected function getDataByTable($tableName, $condition = null, $message, \PDO $pdo){

        if ($condition != null){
            $sqlString = "SELECT * FROM $tableName WHERE $condition"; 
        } else {
             $sqlString = "SELECT * FROM $tableName";
        }

        
        $user = getallheaders()['X-Auth-User'];
        $data = array();
        $errmsg = "";
        $code = 0;
 
        try{
            if($result = $pdo->query($sqlString)->fetchAll()){ 
                foreach($result as $record){ 
                    array_push($data, $record);
                }

                $result = null;
                $code = 200;
                
                $this->logger($user, "GET",  $message);
                return $this->generateResponse($data, "Success", $message, $code);
           
            } else {
                $errmsg = "No data found";
                $code = 404;
            }
        } catch(\PDOException $e) {
            $errmsg = $e->getMessage(); 
            $code = 403; 
        }

        $this->logger($user, "GET",  $errmsg);
        return $this->generateResponse(null, "failed", $errmsg, $code);
    }

    protected function generateResponse($data, $remark, $message, $statusCode){
        $status = array(
            "remark" => $remark,
            "message" => $message
        );

        http_response_code($statusCode);

        return array(
            "payload" => $data,
            "status" => $status,
            "prepared_by" => "Mac and Richard.",
            "date_generated" => date_create()
        );

    }
    
    protected function postData($tableName, $body, $message, \PDO $pdo){
        $values = [];
        $errmsg = "";
        $code = 0;
        $user = getallheaders()['X-Auth-User'];

        
        foreach($body as $value){
            array_push($values,$value);
        }
        
        try {
            $sqlString = $this->generateInsertString($tableName ,(array) $body, true);
            $sql = $pdo->prepare($sqlString);
            $sql->execute($values);

            $code = 200;
            $data = null;

            $this->logger($user, "POST", $message);
            return $this->generateResponse($data, "success", $message, $code);

        } catch(\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }
        $this->logger($user, "POST",  $errmsg);
        return $this->generateResponse(null, "failed", $errmsg, $code);
    }

    private function generateUpdateString($tables, $condition, $body){
        $sqlStrings = [];
        $fields = (array) $body;
        
        foreach ($tables as $table) {
            $setClauses = [];
            foreach ($fields as $key => $value) {
                $setClauses[] = "$key = :$key";  
            }
            
            $setClause = implode(', ', $setClauses);

            $sql = "UPDATE $table SET $setClause WHERE $condition";

            array_push($sqlStrings,$sql);
        }

        return $sqlStrings;
    }

    protected function patchData($tables, $body, $id, $condition, $message, \PDO $pdo) {
        $user = getallheaders()['X-Auth-User'];
        $values = ['id' => $id];
        $sqlStrings = $this->generateUpdateString($tables, $condition, $body);
        $errmsg = "";
        $code = 0;
        
        foreach ($body as $key => $value) {
            $values[":$key"] = $value;  
        }
    
        try {

            $pdo->beginTransaction();

            foreach ($sqlStrings as $sqlString) {
                $sql = $pdo->prepare($sqlString);
                $sql->execute($values); 
                if ($sql->rowCount() == 0) {
                    $pdo->rollBack();

                    $errmsg = "No changes were made.";
                    $this->logger($user, "PATCH",  $errmsg);
                    return $this->generateResponse(null, "failed", $errmsg, 400);
                }
            }
    
            $pdo->commit();

            $this->logger($user, "PATCH",  $message);
            return $this->generateResponse(null, "success", $message, 200);
        
        } catch (\PDOException $e) {
            $pdo->rollBack();
            $errmsg = $e->getMessage();

            $this->logger($user, "PATCH",  $errmsg);
            return $this->generateResponse(null, "failed", $errmsg, 400);
        }
    }

    private function getCurrentBalance($id, \PDO $pdo) {
        $condition = "userId = $id";

        $response = $this->getDataByTable("balance_tbl",$condition, "Successfully retrieved balance.", $pdo);

        if (isset($response['payload'][0]['balance'])) {
            return (float) $response['payload'][0]['balance'];
        }
    
        return 0.00;
    }

    protected function addBalance($tables, $body, $id, $condition, $message, \PDO $pdo){
        $user = getallheaders()['X-Auth-User'];
        try{
            if (!is_numeric($body->balance) || $body->balance <= 0) {
                throw new Exception("Invalid transfer amount.");
            }
            $balance = $this->getCurrentBalance($id, $pdo);

            $body->balance = $balance + $body->balance;

            return $this->patchData($tables, $body, $id, $condition, $message, $pdo);
        } catch (Exception $e) {
            $errmsg = $e->getMessage();
            $this->logger($user, "PATCH",  $errmsg);
            return $this->generateResponse(null, "error", $e->getMessage(), 400);
        }   
    }

    protected function deductBalance($tables, $body, $id, $condition, $message, \PDO $pdo){
        $user = getallheaders()['X-Auth-User'];
        try {
            if (!is_numeric($body->balance) || $body->balance <= 0) {
                throw new Exception("Invalid transfer amount.");
            }
            $balance = $this->getCurrentBalance($id, $pdo);

            if ($balance < $body->balance) {
                throw new Exception("Insufficient funds.");
            }

            $body->balance =  $balance - $body->balance;
    
            return $this->patchData($tables, $body, $id, $condition, $message, $pdo);
        } catch (Exception $e) {
            $errmsg = $e->getMessage();
            $this->logger($user, "PATCH",  $errmsg);
            return $this->generateResponse(null, "error", $errmsg, 400);
        }
       
    }

    protected function createTransaction($userId, $amount, $type,\PDO $pdo, $fromUserId = null) {
        $user = getallheaders()['X-Auth-User'];

        try{
            $sqlString = "INSERT INTO transactions_tbl (user_id, amount, type, from_user_id, created_at) 
                    VALUES (:userId, :amount, :type, :fromUserId, NOW())";
            $stmt = $pdo->prepare($sqlString);
            $stmt->execute([
                ':userId' => $userId,
                ':amount' => $amount,
                ':type' => $type,
                ':fromUserId' => $fromUserId
            ]);
            $message = "Transaction Created Successfully.";
            $this->logger($user, "POST",  $message);
            return $this->generateResponse(null, "Success", $message, 200);
        } catch (Exception $e){
            $errmsg = $e->getMessage();
            $this->logger($user, "POST",  $errmsg);
            return $this->generateResponse(null, "error", $errmsg, 400);
        }
        
        
    }

    protected function transferFunds($body,\PDO $pdo){
        $user = getallheaders()['X-Auth-User'];

        $fromUserId = $body->from_user_id;
        $toUserId = $body->to_user_id;
        $amount = $body->amount;
        
        $tables = ["balance_tbl"];
        $condition = "userId = :id";

        try {

            if (!isset($body->from_user_id, $body->to_user_id, $body->amount)) {
                throw new Exception("Missing required fields.");
            }
            if (!is_numeric($amount) || $amount <= 0) {
                throw new Exception("Invalid transfer amount.");
            }

            $fromBalance = $this->getCurrentBalance($fromUserId, $pdo);
            if ($fromBalance < $amount) {
                throw new Exception("Insufficient funds.");
            }

            $data = new stdClass();
            $data->balance = $amount;

            $this->deductBalance($tables, $data, $fromUserId, $condition, "Successfully deducted balance.", $pdo);
            $type = "transfer_out";
            $this->createTransaction($fromUserId, -$amount, $type, $pdo, $toUserId);
            
            $data1 = new stdClass();
            $data1->balance = $amount;
            $this->addBalance($tables, $data1, $toUserId, $condition, "Successfully added balance.", $pdo);
            $type = "transfer_in";
            $this->createTransaction($toUserId, $amount, $type, $pdo, $fromUserId);

            $message = "Transfer completed.";

            $this->logger($user, "PATCH",  $message);
            return $this->generateResponse(null, "success", $message, 200);
        } catch (Exception $e){
            $errmsg = $e->getMessage();
            $this->logger($user, "PATCH",  $errmsg);
            return $this->generateResponse(null, "error", $errmsg, 400);
        }
        
    }

    protected function isUserExist($username, \PDO $pdo) {
        $condition = "username='$username' AND isdeleted=0";
        $response = $this->getDataByTable("accounts_tbl",$condition, null, $pdo);

        return !empty($response['payload'][0]['username']);
    }

    protected function isUserIdExist($id, \PDO $pdo) {
        $condition = "userId=$id AND isdeleted=0";
        $response = $this->getDataByTable("accounts_tbl",$condition, null, $pdo);

        return !empty($response['payload'][0]['userId']);
    }

    protected function getUserIdByUsername($username, \PDO $pdo) {
        $condition = "username='$username' AND isdeleted=0";
        $response = $this->getDataByTable("accounts_tbl",$condition, null, $pdo);

        return $response['payload'][0]['userId'] ?? null;
    }

    protected function isIdExist($tablename, $condition,  \PDO $pdo) {
        $response = $this->getDataByTable($tablename,$condition, null, $pdo);

        return !empty($response['payload'][0]);
    }

    protected function getRole($id, \PDO $pdo){
        $condition = "userId = $id AND isdeleted=0";
        $response = $this->getDataByTable("users_tbl",$condition, null, $pdo);

        return $response['payload'][0]['roles'] ?? null;
    }

}




?>