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
	$data['id'] = isset($data["id"]) ? $data["id"] : $_REQUEST["id"];
}

if(!empty($api_key) && API_KEY === $api_key){

	if(!empty($data["id"])) {
		$users->id = $data['id'];
		$result = $users->read();	//	Fetching Users Data to verify if User ID exist in the System or not.
		
		if($result->num_rows > 0){
			if($users->delete()){    
				http_response_code(200); 
				echo json_encode(array("message" => "User was deleted Successfully."));
			} else {    
				http_response_code(500);   
				echo json_encode(array("message" => "Unable to delete User."));
			}
		}else{
			http_response_code(404);     
			echo json_encode(array("message" => "User ID does not Exist."));
		}
	} else {
		http_response_code(400);    
		echo json_encode(array("message" => "Unable to delete User. User Id is missing."));
	}
}else{
	http_response_code(401);     
    echo json_encode(array("message" => "Invalid API Key"));
}
?>
