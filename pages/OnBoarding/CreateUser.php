<?php
$AD_server = "ldap://172.18.0.150"; 
$ds = ldap_connect($AD_server);
if ($ds) {
    ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3); // IMPORTANT
    $result = ldap_bind($ds, "cn=Manager,dc=acurusldap,dc=com","Acurus@123"); //BIND
	if (!$result)
	{
	echo 'Not Binded';
	}
    $info["cn"] = "10020";
    $info["sn"] = "10020";
	$info["uid"] = "10020";
    $info["objectclass"] = "inetOrgPerson";
	$info["userPassword"] = "acurus";
    $info["givenName"] = "Nithin";
    $r = ldap_add($ds, "cn=10020,ou=Users,dc=acurusldap,dc=com", $info);
	if ($r)
		{
				echo 'Success';
				$dn = "cn=HOD RCM,ou=HRMS,ou=Test,ou=Acurus,dc=acurusldap,dc=com";
				$entry['memberuid'] = "10020";
				$s = ldap_mod_add($ds, $dn, $entry);
				if ($s)
				{
					echo 'Yes';
				}
		}
   else
		{
			echo ldap_errno($ds) ;
		}
    ldap_close($ds);
}
 else
	 {
		echo "Unable to connect to LDAP server";
	}
?>
