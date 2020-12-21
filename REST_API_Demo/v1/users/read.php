<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../../config/Database.php';
include_once '../../config/Credentials.php';
include_once '../../class/Users.php';

$database = new Database();
$db = $database->getConnection();
$users = new Users($db);

if(isset($_REQUEST) && !empty($_REQUEST)){
	$api_key = $_REQUEST['api_key'];
}else{
	$api_key = $_GET['api_key'];
	$data = json_decode(file_get_contents("php://input"),1);
}

if(!empty($api_key) && API_KEY === $api_key){

    $users->id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : '0';
    $result = $users->read();

    if($result->num_rows == 0 && empty($users->id)){    //	Verifying User Data and if User list then it's not an Error
        http_response_code(200);     
        echo json_encode(array("message" => "Empty User List."));
    }
    else if($result->num_rows == 0 && $users->id !== ''){
        http_response_code(404);     
        echo json_encode(array("message" => "User not found."));
    }
    else{    
        $userRecords=array();
        $userRecords["users"]=array(); 
        while ($user = $result->fetch_assoc()) { 	
            extract($user); 
            $userDetails=array(
                "id" => $id,
                "first_name" => $first_name,
                "age" => $age,
                "points" => $points,
                "address" => $address,            
                "created_at" => $created_at,
                "modified_at" => $modified_at		
            ); 
        array_push($userRecords["users"], $userDetails);
        }    
        http_response_code(200);     
        echo json_encode($userRecords);
    }
}else{
    http_response_code(401);     
    echo json_encode(array("message" => "Invalid API Key"));
} 