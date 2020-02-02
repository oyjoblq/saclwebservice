<?php

  include ("./lib/ws_server_security_header.php");
  include ("./lib/student_model.php");

  // WebService Method Definition
  function getStudentInformation($key,$empl_id) {
    include ("./service_status.php");
    global $stu_lookup;

    $ip_address =  explode(".",$_SERVER['REMOTE_ADDR']);
    $dttm_now = strtotime(date("Y-m-d H:i:s"));

    error_log(print_r($_SERVER['REMOTE_ADDR'], 1));

    if  ( ( check_ws_server_security($ip_address, $key) == TRUE ) &&
          ( $enabled == TRUE)) {

      $my_results = return_student_record($empl_id);
      $status_message = $my_results["status"] . " - " . $my_results["descriptor"] . " - " .$my_results["payload_length"];
      array_unshift( $my_results["payload"], $status_message);
      return $my_results["payload"];

    } else {
      return array( "E100 - Web Service Security Failure (0) - 1" );
    }
  }

  // Turn off WSDL Caching for development period.
  ini_set("soap.wsdl_cache_enabled", "0"); // disabling WSDL cache

  // Bind Code to WS Method - StudentInfoService
  $server = new SoapServer("./symplicity_services.wsdl");
  $server->addFunction("getStudentInformation");
  $server->handle();

?>
