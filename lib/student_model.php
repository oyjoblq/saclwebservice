<?php

function return_student_record( $empl_id ) {

  define('APP_DB_HOST', 'fake-server.umas.edu');
  define('APP_DB_USER', 'fake_user');
  define('APP_DB_CRED', 'fake_password');
  define('APP_DB_DEFAULT', 'fake_database');
  
  $results = array ();
  $results["status"] = "E200";
  $results["descriptor"] = "DB Connection Failure (0)";
  $results["payload_length"] = 1;
  $results["payload"] = array();

  $student_record = array();

  $mysqli = new mysqli(APP_DB_HOST,APP_DB_USER,APP_DB_CRED,APP_DB_DEFAULT);
  if ($mysqli->connect_errno > 0) {
    die('Unable to connect to database [' . $mysqli->connect_error . ']: ' . mysqli_error($mysqli));
  }

  $query = "SELECT empl_id,
	last_name,
	first_name,
	middle_initial,
	full_name,
	sex,
	birthdate,
	under_18_flag,
	over_21_flag,
	ferpa_flag,
	admit_term,
	admit_term_descr,
	expect_grad_term,
	expect_grad_term_descr,
	prim_acad_career,
	prim_acad_prog,
	prim_acad_prog_status,
	prim_acad_prog_status_descr,
	prim_acad_plan,
	curr_assign_building,
	curr_assign_room,
	mail_address1,
	mail_address2,
	mail_city,
	mail_state_prov,
	mail_postal_code,
	perm_address1,
	perm_address2,
	perm_city,
	perm_state_prov,
	perm_postal_code,
	perm_phone,
	local_phone,
	oit_email,
	Athlete,
	Sport,
	RA_PM,
	on_campus,
	last_updated_dttm
  FROM Student_Conduct_v3 WHERE empl_id like '%" . $empl_id . "%' Limit 50";

  if ($result = $mysqli->query($query)) {

    // So the result returned true, let's loop and print out each city.
    // The number of rows returned is assigned to the property "num_rows" in the mysql_result class

    //
    // Use If-Then constructs to sort out what was returned.
    // 
    if ($result->num_rows == 1) {
      if ($row = $result->fetch_array(MYSQLI_NUM)) {
        $results["payload"] = $row;
        $results["status"] = "S100";
        $results["descriptor"] = "Single Match (" . ( $result->num_rows ) . ")";
        $results["payload_length"] = $mysqli->field_count + 1;

      } else {
        $results["status"] = "E300";
        $results["descriptor"] = "Can't Return the Row (1)";
        $results["payload_length"] = 1;

      } // end if (fetch_array)
    } // end if (num_rows == 1)

    if ($result->num_rows == 0) {
      $results["status"] = "E400";
      $results["descriptor"] = "No Matching Results (" . ( $result->num_rows ) . ")";
      $results["payload_length"] = 1;
    } // end if (num_rows == 0)

    if ($result->num_rows > 1) {
      $results["status"] = "E500";
      $results["descriptor"] = "Multiple Matches (" . ( $result->num_rows ) . ")";
      $results["payload_length"] = 1;
    } // end if (num_rows > 1)

  } else {
    // Notice below that the errors are still contained within the mysqli class.
    // This means that each result will affect a single "error" property.
    // In otherwords, if your result fails, the error returned from MySQL iss
    // assigned to the property "error".

    // This means the query failed
    echo $mysqli->error;

  } // end else

  $mysqli->close();

  // Use logic in the following section to lookup fake data or overwrite and real data during testing.
  if (1==0) {
    $results["status"] = "S100";
    $results["descriptor"] = "Single Match (1)";
    $results["payload_length"] = 31;
    $results["payload"] = array(
      "empl_id" => "22222222",
      "last_name" => "Smith",
      "first_name" => "John",
      "middle_initial" => "W",
      "full_name" => "Smith, John W",
      "sex" => "M",
      "birthdate" => "2000-01-01",
      "under_18_flag" => "N",
      "over_21_flag" => "Y",
      "ferpa_flag" => "N",
      "admit_term" => "1087",
      "expect_grad_term" => "1113",
      "prim_acad_career" => "UGRD",
      "prim_acad_prog" => "UGRD",
      "prim_acad_plan" => "BS-GEOL",
      "curr_assign_building" => "BAKE",
      "curr_assign_room" => "0402A",
      "mail_address1" => "0402A Baker Hall, Amherst",
      "mail_address2" => "100 Main St.",
      "mail_city" => "Amherst",
      "mail_state_prov" => "MA",
      "mail_postal_code" => "01063-0000",
      "perm_address1" => "22 BakerSt.",
      "perm_address2" => "Apt B",
      "perm_city" => "London",
      "perm_state_prov" => "GB",
      "perm_postal_code" => "A1B2C3",
      "perm_phone" => "0441231444444",
      "local_phone" => "4135454444",
      "oit_email" => "test@umas.edu",
      "last_updated_dttm" => date("Y-m-d H:i:s")
    );
  
  }

  return $results;
}

?>
