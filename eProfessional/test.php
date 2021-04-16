<?php

function connectToMyDB(){
	$link = mysql_connect( "localhost", "dbauto", "password" );
	if( !mysql_select_db( "eprofessional", $link ) ){
		die( "Connect error" );
	}
}

if( !isset( $_POST['submit'] ) ){
	
	connectToMyDB();

	// Run query to get initial data
	$query = mysql_query( "SELECT * FROM `students` ORDER BY `STUDENT_CODE` ASC" )
				or die( mysql_error() );
		
	$i = 1; $form = "";
	
	// Add all our data to a form variable for iteration
	while( $row = mysql_fetch_array( $query ) ){
		
		$form .= "<tr>";
		$form .= "<input type='hidden' name='" . $i . "-code' value='" . $row['STUDENT_CODE'] . "' />";
		$form .= "<td style='width: 100px;'>" . $row['STUDENT_CODE'] . "</td>";
		$form .= "<td style='width: 100px;'>" . $row['YEAR_LEVEL'] . "</td>";
		$form .= "<td style='width: 100px;'>" . $row['CLASS'] . "</td>";
		$form .= "<td style='width: 100px;'><input type='text' name='" . $i . "-score' value='" . $row['SCORE'] . "' /></td>";
		$form .= "<td style='width: 100px;'><input type='text' name='" . $i . "-date' value='" . $row['SITTING_DATE'] . "' /></td>";
		$form .= "</tr><br />";
		$i++;
		
	}
	
}else{
	
	// In the example above I've isolated three fields. $i-code, $i-score, and $i-date. We will iterate
	// through all of them to populate our array. Then, we'll hit the DB
	
	// First, let's get the total number of people we need to hit
	connectToMyDB();
	
	$query = mysql_query( "SELECT * FROM `students`" )
				or die( mysql_error() );
	$total = mysql_num_rows( $query );
	
	for( $i = 1; $i <= $total; $i++ ){
		
		// This part is kind of confusing. I am basically creating a multidimensional array that holds
		// the score information and date information in the index of the student code. I can then
		// use that to update the database (as you see in the foreach() loop below)
		$data[$_POST[$i . "-code"]]['SCORE'] = $_POST[$i . "-score"];
		$data[$_POST[$i . "-code"]]['DATE'] = $_POST[$i . "-date"];
		
	}

	foreach( $data as $key => $value ){
		
		$studentScore = $value['SCORE'];
		$studentDate =  $value['DATE'];
		
		// We use UPDATE if the entry is already in the database. Which it obviously is since we just called data.
		$query = mysql_query( "UPDATE `students` SET `SCORE` = '" . $studentScore . "', `SITTING_DATE` = '" . $studentDate . "' WHERE `STUDENT_CODE` = '" . $key . "'" )
					or die( mysql_error() );
		
	}
	
	// Re-display the page with the updated information
	header( "location: " . $_SERVER['PHP_SELF'] );
	
}

?>

<html>

<title>Students form</title>

<body>

	<form action="<?php echo( $_SERVER['PHP_SELF'] ); ?>" method="post" >
    
    	<?php echo( $form ); ?>
        
        <input type="submit" name="submit" value="Submit!"  />
    
    </form>

</body>

</html>