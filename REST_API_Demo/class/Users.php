<?php
class Users{   
    
    private $usersTable = "users";      
    public $id;
    public $first_name;
    public $age;
    public $points;
    public $address;   
	public $created_at; 
	public $modified_at; 
    private $conn;
	
    public function __construct($db){
        $this->conn = $db;
    }	
	
	function read(){	
		
		if($this->id) {
			$stmt = $this->conn->prepare("SELECT * FROM ".$this->usersTable." WHERE id = ?");
			$stmt->bind_param("i", $this->id);					
		} else {
			$stmt = $this->conn->prepare("SELECT * FROM ".$this->usersTable." ORDER BY points DESC ");		
		}		
		$stmt->execute();			
		$result = $stmt->get_result();		
		return $result;	
	}
	
	function create(){
		
		$stmt = $this->conn->prepare("
			INSERT INTO ".$this->usersTable."(`first_name`, `age`, `points`, `address`, `created_at`)
			VALUES(?,?,?,?,?)");
		
		$this->first_name = htmlspecialchars(strip_tags($this->first_name));
		$this->age = htmlspecialchars(strip_tags($this->age));
		$this->points = htmlspecialchars(strip_tags($this->points));
		$this->address = htmlspecialchars(strip_tags($this->address));
		$this->created_at = htmlspecialchars(strip_tags($this->created_at));
		
		
		$stmt->bind_param("siiss", $this->first_name, $this->age, $this->points, $this->address, $this->created_at);
		
		if($stmt->execute()){
			return true;
		}
	 
		return false;		 
	}
		
	function update(){
	 
		$stmt = $this->conn->prepare("
			UPDATE ".$this->usersTable." 
			SET points = ?
			WHERE id = ?");
	 
		$this->id = htmlspecialchars(strip_tags($this->id));
		$this->points = htmlspecialchars(strip_tags($this->points));
		
		$stmt->bind_param("ii", $this->points, $this->id);
		
		if($stmt->execute()){
			return true;
		}
	 
		return false;
	}
	
	function delete(){
		
		$stmt = $this->conn->prepare("
			DELETE FROM ".$this->usersTable." 
			WHERE id = ?");
			
		$this->id = htmlspecialchars(strip_tags($this->id));
	 
		$stmt->bind_param("i", $this->id);
	 
		if($stmt->execute()){
			return true;
		}
	 
		return false;		 
	}
}
?>