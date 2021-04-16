<?php
/*******************************************
 * Facebook Tagging Script
 * Sample PHP handling file
 * Coded by: S. Chism
 * Distributed under the GNU Public License
 *******************************************/
 
# Check to see if the form has been submitted or not:
if( !isset( $_POST['submit'] ) ){
	
	# Form has not been submitted, show our uploader form and stop the script
	require_once( "uploader.html" );
	exit();
	
}else{
	
	# Form has been submitted, begin processing data
	
	$imageUpload = $_FILES['picture_upload'];
	
	# Include the function file then call it with the uploaded picture:
	# TIP: The "../../ portion is a relative path. You will need to change this
	#      path to fit your website's directory structure.
	require_once( '../../facebook_tagger.php' );
	
	if( !fbtTagger( $imageUpload ) ){
	
		# If the function returns false, format and output the error
		# REMINDER: The output variable names (such as "$fbtOutput") are
		# 			defined in the facebook_tagger configuration section.
		$fbtOutput = "<p><span style='font-weight: bold; color: #930000;'>&bull;&nbsp;" . $fbtOutput . "</span></p>";
		require_once( "uploader.html" );
		exit();
	
	}else{
	
		# We know the image tag will work so lets run the script and then
		# output the results.
		# REMINDER: The output variable names (such as "$fbtFinalImage") are
		# 			defined in the facebook_tagger configuration section.
		
		fbtTagger( $imageUpload, true );
		
		# After invoking fbtTagger(), we now have access to two variables. $fbtOutput which will be giving us our success
		# message and $fbtFinalImage which is pointing us the local URL of the new created image
		require_once( "success.html" );
		
	}
	
}	

# That's all, folks!
?>
    