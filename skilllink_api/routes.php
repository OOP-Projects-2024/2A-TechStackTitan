<?php
    require_once "./config/database.php";
    require_once "./modules/Get.php";
    require_once "./modules/Post.php";
    require_once "./modules/Patch.php";
    require_once "./modules/Delete.php";
    require_once "./modules/Auth.php";
    require_once "./modules/Crypt.php";

    class API{
        private $pdo;
        private $get;
        private $post;
        private $patch;
        private $delete;
        private $auth;
    
        public function __construct($pdo) {
            $this->pdo = $pdo;
            $this->get = new Get($pdo);
            $this->post = new Post($pdo);
            $this->patch = new Patch($pdo);
            $this->delete = new Delete($pdo);
            $this->auth = new Authentication($pdo);
        }

        public function handleRequest($method, $request, $body = null) {
            switch ($method) {
                case "GET":
                    return $this->handleGet($request);
                case "POST":
                    return $this->handlePost($request, $body);
                case "PATCH":
                    return $this->handlePatch($request, $body);
                case "DELETE":
                    return $this->handleDelete($request);
                default:
                    http_response_code(400);
                    return "Invalid Request Method.";
            }
        }

        private function handleGet($request) {
            if (!$this->auth->isAuthorized()) {
                http_response_code(403);
                return "Unauthorized.";
                exit();
            }
            switch($request[0]){
                case "users":
                    return $this->handleGetUsers($request);
                break;

                case "services":
                    return $this->handleGetServices($request);
                break;

                case "transactions":
                    return json_encode($this->get->getTransactions($request[1] ?? null));
                break;

                case "balance":
                    return json_encode($this->get->getBalance($request[1] ?? null));
                break;

                case "bookings":
                    return json_encode($this->get->getBookings($request[1] ?? null));
                break;

                case "messages":
                    return json_encode($this->get->getMessages($request[1] ?? null));
                break;

                case "reviews":
                    return json_encode($this->get->getReviews($request[1] ?? null));
                break;

                case "log":
                    if (!$this->auth->isAdmin()) {
                        http_response_code(403);
                        return "Permission denied.";
                        exit();
                    }
                    return json_encode($this->get->getLogs($request[1] ?? null));
                break;
                
                default:
                    http_response_code(401);
                    return "This is invalid endpoint";
                break;
            }
        }

        private function handleGetUsers($request){
            if (count($request) < 3){
                if((isset($request[1]) && is_numeric($request[1])) || empty($request[1])){
                    return json_encode($this->get->getUsers($request[1] ?? null));
                }
            }
            if (isset($request[2])){
                switch($request[2]){
                    case "balance":
                        return json_encode($this->get->getBalance($request[1]));
                    break;
    
                    case "reviews":
                        return json_encode($this->get->getUserReviews($request[1]));
                    break;
    
                    case "bookings":
                        return json_encode($this->get->getUserBookings($request[1]));
                    break;
    
                    case "transactions":
                        return json_encode($this->get->getUserTransactions($request[1]));
                    break;
    
                    case "messages":
                        if (!isset($request[3])) {
                            http_response_code(400);
                            return "Insufficient parameters provided for the 'messages' endpoint.";
                            exit();
                        }
                        switch($request[3]){
                            case "sent":
                                return json_encode($this->get->getUserMessages("sent",$request[1]));
                            break;
                            
                            case "received":
                                return json_encode($this->get->getUserMessages("received",$request[1]));
                            break;
    
                            default:
                                http_response_code(401);
                                return "This is invalid endpoint";
                            break;
                        }
                    break;
                    
                    default:
                        http_response_code(401);
                        return "This is invalid endpoint";
                    break;
                }
            }

            http_response_code(401);
            return "This is invalid endpoint";
        }

        private function handleGetServices($request){
            if(count($request) > 1){

                switch($request[1]){
                    case "offers":
                        return json_encode($this->get->getOffers($request[2] ?? null));
                    break;

                    case "requests":
                        return json_encode($this->get->getRequests($request[2] ?? null));
                    break;


                    default:
                        http_response_code(401);
                        return "This is invalid endpoint";
                    break;
                }
            
            } else {
                http_response_code(400);
                return "Insufficient parameters provided for the 'services' endpoint.";
            }
        }

        private function handlePost($request, $body) {
            $noAuthRoutes = ["users/register", "users/login"];

            if (!in_array($_REQUEST['request'], $noAuthRoutes) && !$this->auth->isAuthorized()) {
                http_response_code(403);
                return "Unauthorized.";
                exit();
            }

            switch($request[0]){
                case "users":
                    return $this->handlePostUsers($request, $body);
                break;
                
                case "services":
                    return $this->handlePostServices($request, $body);
                break;

                case "transactions";
                    return json_encode($this->post->postTransactions($body));
                break;

                case "bookings";
                    return json_encode($this->post->postBookings($body));
                break;

                case "messages";
                    return json_encode($this->post->postMessages($body));
                break;

                case "reviews";
                    return json_encode($this->post->postReviews($body));
                break;

                default:
                    http_response_code(401);
                    return "This is invalid endpoint";
                break;
            }
        }

        private function handlePostUsers($request, $body){
            if(count($request) > 1){

                switch($request[1]){
                    case "register":
                        return json_encode($this->auth->addAccount($body));
                    break;
                    
                    case "login":
                        return json_encode($this->auth->login($body));
                    break;

                    default:
                        http_response_code(401);
                        return "This is invalid endpoint";
                    break;
                }
            } else {
                http_response_code(400);
                return "Insufficient parameters provided for the 'users' endpoint.";
            }
        }

        private function handlePostServices($request, $body){
            if(count($request) > 1){

                switch($request[1]){
                    case "offer":
                        return json_encode($this->post->postServices($body));
                    break;

                    case "request":
                        return json_encode($this->post->postRequest($body));
                    break;

                    default:
                        http_response_code(401);
                        return "This is invalid endpoint";
                    break;
                }
            } else {
                http_response_code(400);
                return "Insufficient parameters provided for the 'services' endpoint.";
            }
        }

        private function handlePatch($request, $body){
            if (!$this->auth->isAuthorized()) {
                http_response_code(403);
                return "Unauthorized.";
                exit();
            }
            switch($request[0]){
                case "users":
                    if(count($request) > 1){
                        return json_encode($this->patch->patchUsers($body,$request[1]));
                    } else {
                        http_response_code(400);
                        return "Insufficient parameters provided for the 'users' endpoint.";
                    }
                break;

                case "services":
                    return $this->handleServicesPatch($request, $body);
                break;

                case "transactions";
                    if (!$this->auth->isAdmin()) {
                        http_response_code(403);
                        return "Permission denied.";
                        exit();
                    }
                    return json_encode($this->patch->patchTransaction($body, $request[1]));
                break;
                

                case "balance":
                    return $this->handleBalancePatch($request, $body);
                break;

                case "bookings":
                    if(count($request) > 1){
                        return json_encode($this->patch->patchBookings($body,$request[1]));
                    } else {
                        http_response_code(400);
                        return "Insufficient parameters provided for the 'bookings' endpoint.";
                    }
                break;

                case "messages":
                    if(count($request) > 1){
                        return json_encode($this->patch->patchMessages($body,$request[1]));
                    } else {
                        http_response_code(400);
                        return "Insufficient parameters provided for the 'messages' endpoint.";
                    }
                break;

                case "reviews":
                    if(count($request) > 1){
                        return json_encode($this->patch->patchReviews($body,$request[1]));
                    } else {
                        http_response_code(400);
                        return "Insufficient parameters provided for the 'reviews' endpoint.";
                    }
                break;

                default:
                    http_response_code(401);
                    return "This is invalid endpoint";
                break;
            }
        }

        private function handleServicesPatch($request, $body){
            if(count($request) > 1){
                    
                switch($request[1]){
                    case "offer":
                        return json_encode($this->patch->patchOffer($body,$request[2]));
                    break;

                    case "request":
                        return json_encode($this->patch->patchRequest($body,$request[2]));
                    break;

                    default:
                        http_response_code(401);
                        return "This is invalid endpoint";
                    break;
                }

            } else {
                http_response_code(400);
                return "Insufficient parameters provided for the 'services' endpoint.";
            }
        }

        private function handleBalancePatch($request, $body){
            if(count($request) > 1){
                    
                switch($request[1]){
                    case "deposit":
                        return json_encode($this->patch->Deposit($body, $request[2]));
                    break;

                    case "withdraw":
                        return json_encode($this->patch->Withdraw($body, $request[2]));
                    break;

                    case "transfer":
                        return json_encode($this->patch->Transfer($body));
                    break;

                    default:
                        http_response_code(401);
                        return "This is invalid endpoint";
                    break;
                }

            } else {
                http_response_code(400);
                return "Insufficient parameters provided for the 'balance' endpoint.";
            }
        }

        private function handleDelete($request){
            if (!$this->auth->isAuthorized()) {
                http_response_code(403);
                return "Unauthorized.";
                exit();
            }
            switch($request[0]){
                case "users":
                    return json_encode($this->patch->archiveUsers($request[1]));
                break;

                case "services":
                    return $this->handleServicesDelete($request);
                break;

                case "bookings":
                    if(count($request) > 1){
                        return json_encode($this->patch->cancelBooking($request[1]));
                    } else {
                        http_response_code(400);
                        return "Insufficient parameters provided for the 'bookings' endpoint.";
                    }
                break; 

                case "messages":
                    if(count($request) > 1){
                        return json_encode($this->patch->archiveMessages($request[1]));
                    } else {
                        http_response_code(400);
                        return "Insufficient parameters provided for the 'messages' endpoint.";
                    }
                break;

                case "reviews":
                    if(count($request) > 1){
                        return json_encode($this->patch->archiveReviews($request[1]));
                    } else {
                        http_response_code(400);
                        return "Insufficient parameters provided for the 'reviews' endpoint.";
                    }
                break;

                default:
                    http_response_code(401);
                    return "This is invalid endpoint";
                break;
            }
        }

        private function handleServicesDelete($request){
            if(count($request) > 1){
                switch($request[1]){
                    case "offer":
                        return json_encode($this->patch->archiveOffers($request[2]));
                    break;
    
                    case "request":
                        return json_encode($this->patch->archiveRequests($request[2]));
                    break;
    
                    default:
                        http_response_code(401);
                        return "This is invalid endpoint";
                    break;
                }
            } else {
                http_response_code(400);
                return "Insufficient parameters provided for the 'services' endpoint.";
            }
        }
        
    }

    $db = new Connection();
    $pdo = $db->connect();
    $api = new API($pdo);

    $request = isset($_REQUEST['request']) ? explode("/", $_REQUEST['request']) : [];
    $body = json_decode(file_get_contents("php://input"));

    echo $api->handleRequest($_SERVER['REQUEST_METHOD'], $request, $body);
?>