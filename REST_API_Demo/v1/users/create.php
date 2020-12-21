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

$data = json_decode(file_get_contents("php://input"),1);
if(isset($_REQUEST) && !empty($_REQUEST)){
    $api_key = $_REQUEST['api_key'];
	$data['first_name'] = isset($data['first_name']) ? $data['first_name']: $_REQUEST['first_name'];
	$data['age'] = isset($data['age']) ? $data['age'] : $_REQUEST['age'];
}

if(!empty($api_key) && API_KEY === $api_key){

    if(!empty($data['first_name']) && !empty($data['age'])){    

        $users->first_name = $data['first_name'];
        $users->age = $data['age'];
        $users->points = (isset($data['points']) && $data['points']) ? $data['points'] : '0';
        $users->address = (isset($data['address']) && $data['address']) ? $data['address'] : '';	
        $users->created_at = date('Y-m-d H:i:s'); 
        
        if($users->create()){         
            http_response_code(201);         
            echo json_encode(array("message" => "User was created Successfully."));
        } else{         
            http_response_code(500);        
            echo json_encode(array("message" => "Unable to create User."));
        }
    }else{    
        http_response_code(400);    
        echo json_encode(array("message" => "Unable to create User. Data is incomplete. Please Provide First Name and Age."));
    }
}else{
    http_response_code(401);     
    echo json_encode(array("message" => "Invalid API Key"));
}
?>
