<?php
$empids = array(5034,
5039,
5053,
5123,
5128,
5131,
5140,
5143,
5145,
5154,
5161,
5162,
5176,5177,5178,5179,
5181,5183,5184,
5186,
5188,
5191,
5192,
5196,
5197,
5198,
5201,
5202,
5204,
5206,
5214,
5217,
5218,
5225,
5228,
5233,
5234,
5236,
5239,
5241,
5242,
5245,
5253,
5254,
5258,
5259,
5262,
5263,
5269,
5270,
5280,
5283,
5285,5286,5287,5289,
5294,
5295,5296,5298,5299,
5301,5302,
5304,5307,5308,
5312,5314,
5318,
5320,5321,
5325,5329,
5330,5334,
5338,5342,5343,5344,
5346,5347,
5348,5349,5350,5353,
5354,5355,5358,
5360,5362,
5364,5365,5366,5367,5368,5369,
5370,5372,5374,
5376,5380,5382,5383,5384,
5385,5386,5391,5392,5393,5394,5396,
5397,5398,5400,5402,5403
);
$AD_server = "ldap://172.18.0.150"; 
		$ds = ldap_connect($AD_server);
		if ($ds) {
					ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3); // IMPORTANT
					$result = ldap_bind($ds, "cn=Manager,dc=acurusldap,dc=com","Acurus@123"); //BIND
        
					if (!$result)
						{
							echo 'Not Binded';
						}
        foreach($empids as $Emplid)
        {
						$userDn = "cn=".$Emplid.",ou=Users,dc=acurusldap,dc=com";
       					 $dn = "cn=Employee,ou=HRMS,ou=Test,ou=Acurus,dc=acurusldap,dc=com";
						$entry['memberuid'] = $Emplid;
						$s = ldap_mod_del ($ds, $dn, $entry);
        echo 'Deleted'.$Emplid;
        }
   		 ldap_close($ds);
			}
?>