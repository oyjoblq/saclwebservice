<?php


function check_ws_server_security($ip_address, $key){
  $is_ok = FALSE;

  $ip_on_campus = FALSE;
  $ip_admin_ok = FALSE;
  $ip_symplicity_ok = FALSE;
  $ip_firewalled_subnet = FALSE;

// Date Time Variables
  $dttm_is_active = FALSE;
  $dttm_now = strtotime(date("Y-m-d H:i:s"));
  $dttm_beg = strtotime("2017-08-31 00:00:00");
  $dttm_end = strtotime("2019-06-30 23:59:59");

// Valid Key Variables
  $key_ok = FALSE;

  $key_symmetric = "fakedinfood";
  $key_symmetric2 = "fakeedinfofrerwre";

// localhost check
 if ($ip_address[0] == '::1') { $ip_admin_ok = TRUE; };

// On IP Campus Check. Note by LO: All IPs are faked information.
  if (( $ip_address[0] == '139' ) AND ( $ip_address[1] == '109')) { $ip_on_campus = TRUE; }
  if (( $ip_address[0] == '87' ) AND ( $ip_address[1] == '69')) { $ip_on_campus = TRUE; }

// Admin IP Check
  if ( ( $ip_address[0] == '148' ) AND ( $ip_address[1] == '179')
       AND (
                ( ( $ip_address[2] == '159') AND ( $ip_address[3] == '36') ) 
             OR ( ( $ip_address[2] == '146') AND ( $ip_address[3] == '37') ) 
            )
     ) { $ip_admin_ok = TRUE; }

// Symplicity Testing Subnet
   if ( implode(".", array( $ip_address[0], $ip_address[1], $ip_address[2])) == '88.171.178') { $ip_symplicity_ok = TRUE; }

// Symplicity Dev Machines
  if ( ( $ip_address[0] == '645' ) AND ( $ip_address[1] == '54')
       AND ( ( $ip_address[2] == '132') AND ( $ip_address[3] >= '54') AND ( $ip_address[3] <= '91') )
     ) { $ip_symplicity_ok = TRUE; }

// Firewalled IP Rage Check
  if (( $ip_address[0] == '168' ) AND
      ( $ip_address[1] == '125')  AND
      ( $ip_address[2] == '1') 
     ) {
    $ip_firewalled_subnet = TRUE; 
  }

//158.132.430.126 -- Test CentOS Server
// New IP for New Local Conduct Severs - CentOS
  if (( $ip_address[0] == '158' ) AND
      ( $ip_address[1] == '132')  AND
      ( $ip_address[2] == '430') 
    AND  ( ( $ip_address[3] == '128') OR ( $ip_address[3] == '162')) // umascs-test and host IP
     ) {
    $ip_firewalled_subnet = TRUE; 
  }

// New IPs for Amazon Cloud 12/06/2018
  $full_ip_addr = implode(".", array($ip_address[0], $ip_address[1], $ip_address[2], $ip_address[3]));
  if ($full_ip_addr == '45.143.650.263' OR $full_ip_addr == '64.356.217.565') {
    $ip_firewalled_subnet = TRUE; 
  }

// Active Application Check
  if ( ( $dttm_beg <= $dttm_now ) AND ( $dttm_now <= $dttm_end )) { $dttm_is_active = TRUE; }

//Symmetric Key Check
  if (($key == $key_symmetric) || ($key == $key_symmetric2)) { $key_ok = TRUE; } else { $key_ok = FALSE; }


// RULES
// Default is FALSE. First come things that are allowed. Then show-stoppers to set to FALSE.

  if ( $dttm_is_active AND $ip_firewalled_subnet )  { $is_ok = TRUE; }
  if ( $dttm_is_active AND $ip_admin_ok )  { $is_ok = TRUE; }
  if ( $dttm_is_active AND $ip_symplicity_ok )  { $is_ok = TRUE; }

  if ( ! $key_ok == TRUE )  { $is_ok = FALSE; }


  return $is_ok;
}
?>
