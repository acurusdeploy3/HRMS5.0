<?php 
include_once("config.php");

class LDAPAuth
{
	var $conn; 
	var $host		= "ldap://172.18.0.150";
	var $port		= 389;
	var $group_dn	= "ou=Groups,dc=acurusldap,dc=com";

	
	var $users_dn	= "ou=Users,dc=acurusldap,dc=com";
	var $company	= "acurusldap";
	var $admin_user = "cn=Manager,dc=acurusldap,dc=com";
	
	var $admin_pass = 'Acurus@123';
	var $user_name;
	var $new_user;
	var $user_pass;
	var $group		= "HR_HRMS";
	var $group1		= "Manager_HRMS";
	var $group2		= "Employee_HRMS";
	
	
	function connect_ldap()
	{
		$this->conn = ldap_connect($this->host, $this->port) or die("Could not connect to $this->host");
		ldap_set_option($this->conn, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($this->conn, LDAP_OPT_REFERRALS, 0);
	}

	function login()
	{
		//echo $this->user_name.','.$this->user_pass; exit;
		
		$user = "cn=".$this->user_name.",$this->users_dn";
		$bind = ldap_bind($this->conn,$user,$this->user_pass);
		return $bind;
	}
		function getCN($dn)
	{
		preg_match('/[^,]*/',$dn,$matchs, PREG_OFFSET_CAPTURE, 3);
		return $matchs[0][0];
	}
	function validategroup()
	{
		$search_filter	=	"(cn=".$this->group.")";
		$result_info	= 	ldap_get_entries(
												$this->conn, 
												ldap_search( 
															$this->conn, 
															$this->group_dn, 
															$search_filter,										 
															array('memberUid') 
															)
											);
		$cn_user		= 	$this->getCN("cn=".$this->user_name.",$this->users_dn");
		$dn_group 		= 	$result_info[0]['dn'];
		$search_result 	= 	ldap_search(	
								$this->conn,
								$dn_group,
								"(memberUid=".$cn_user.")",
								array('gidNumber')
							);
		$get_entry 		= 	ldap_get_entries( $this->conn, $search_result );
		return $get_entry["count"];
		
	}
	
	function validategroupmanager()
	{
		$search_filter1	=	"(cn=".$this->group1.")";
		$result_info	= 	ldap_get_entries(
												$this->conn, 
												ldap_search( 
															$this->conn, 
															$this->group_dn, 
															$search_filter1,										 
															array('memberUid') 
															)
											);
		$cn_user		= 	$this->getCN("cn=".$this->user_name.",$this->users_dn");
		$dn_group 		= 	$result_info[0]['dn'];
		$search_result1 	= 	ldap_search(	
								$this->conn,
								$dn_group,
								"(memberUid=".$cn_user.")",
								array('gidNumber')
							);
		$get_entry1 		= 	ldap_get_entries( $this->conn, $search_result1 );
		return $get_entry1["count"];
		
	}
	
	function changepassword()
	{
		if($this->conn) $this->disconnect_ldap(); // disconnect if active connection available
		
		$this->conn = ldap_connect($this->host, $this->port) or die("Could not connect to $this->host");
		ldap_set_option($this->conn, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($this->conn, LDAP_OPT_REFERRALS, 0);
		$r=ldap_bind($this->conn,$this->admin_user, $this->admin_pass);
		
		//$userDn = "cn=nidhini,ou=Users,dc=acurusldap,dc=com"; //,dc=acurusldap,dc=com";
		
		 $var1=$_POST["username"];
		// $_SESSION['login_user']=$var1; 
		$userDn = "cn=".$var1.",ou=Users,dc=acurusldap,dc=com";
		$newPassword =$_POST['userPassword'];
		$newPassword1 =$_POST['confirmPassword'];
		
		$message = " Passwords do not match";
		$userdata = array("userPassword" => "$newPassword");	
					
			if($newPassword == $newPassword1)
			{
				$result = ldap_modify($this->conn, $userDn, $userdata);
			}
			else
			{
				echo " $message";
			}		
			if ($result) 
			{
			header("location:index.php");  
			}//echo "Password Changed." ;
			else echo "";
	}
	
	function validatepage()
	{
	$query= "SELECT count(*) as viewcount FROM employee_details where employee_id='921'";
					$result2 = $db_handle->runQuery($query);
					$count_rows= $result2[0]['viewcount'];
					return $count_rows;
	}
	
	
	function disconnect_ldap()
	{
		ldap_close($this->conn);
	}
	
	
}

?>

