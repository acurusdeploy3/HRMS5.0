<?php 
session_start();
class Db {
    private $hostname = '172.18.0.10:3306';
    private $username = 'root';
    private $password = 'Capella@20!4';
    private $database = 'ahrms';
    private $conn = NULL;
    public function __construct() { 
        $this->conn = mysqli_connect($this->hostname, $this->username, $this->password, $this->database); 
        if(!$this->conn) {
            echo 'Database not connected';
        }
    }
 
    public function getRoleData($Desgn, $Dept, $Proj, $Empl){
	   $sWhere = '';
        $where = array();
        if($Desgn != '') {
			$Desgn = trim($Desgn);
          //  $where[] = 'V.city_id = '.$cityid.' AND V.is_enabled = "1"';
            $where[] = "designation_id like '%$Desgn%' and is_active='Y'";
        }
        if($Dept != '') {
			
				$Dept = trim($Dept);
            //$where[] = 'V.vid = '.$placeid;
            $where[] = "a.department like '%$Dept%' and is_active='Y'";
			
        }
        
		if($Proj != '') {
            $Proj = trim($Proj);
            //$where[] = "( V.visiting_place LIKE '%$keyword%' OR  V.history LIKE '%$keyword%'  OR  C.city LIKE '%$keyword%' )";
            $where[] = "a.project_id like '%$Proj%' and is_active='Y'";
        }
		
		if($Empl != '') {
            $Empl = trim($Empl);
            $where[] = "a.employee_id like '%$Empl%' and is_active='Y'";
        }

        $sWhere     = implode(' AND ', $where);
        if($sWhere) {
            $sWhere = 'WHERE '.$sWhere;
        } 
        if(($Desgn != '') || ($Dept != '') || ($Proj != '') || ($Empl != '')) {
           // $query = "SELECT * FROM visiting_places AS V JOIN tourist_city AS C ON C.city_id = V.city_id $sWhere ";
            $query = "select a.employee_id,concat(first_name,' ',last_name,' ',MI) as name,designation_id,a.department,project_name

			from resource_management_table a join employee_details b on a.employee_id=b.employee_id
				join all_projects c on c.project_id=a.project_id $sWhere ";
            $result = mysqli_query($this->conn, $query);
			$_SESSION['query']= $query;
            return $result;
			
			
        }
    }
	

	
}
?>