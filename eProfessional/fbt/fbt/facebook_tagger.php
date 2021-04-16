<?php
/*******************************************
 * Facebook Tagging Script
 * Version: FBT v1.1.0
 * Coded by: S. Chism
 * Distributed under the GNU Public License
 *******************************************/

function fbtTagger( $tmpImage, $goForIt = false ){
	
	# Configuration Section
	
	## Folder and variable options
	$fbtRootPath    = "../../../fbt/";		// Path to the containing folder
	$fbtResFolder   = "resources/";      	// Resources folder name
	$fbtProFolder	= "resources/processed/";// Folder you would like the processed images saved to
	$fbtOverlay     = "overlay.png";   		// Overlay image filename and extension
	$fbtWatermark	= "watermark.png";		// Watermark image filename and extension
	$fbtOopsImage	= "oops.jpg";			// If an unexpected or unknown error occurs, output this image
	$fbtTextReturn  = "fbtOutput";      	// Variable name for text return
	$fbtImageReturn = "fbtFinalImage";  	// Variable name for the image return

	## Storage and server options
	$fbtMaxFileSize = 20;					// Max file size allowed (in MB)
	
	## Text Return Options
	## In this section you can set the text that is returned based on the
	## following cirumstances. Please do not replace text in brackets (ie. [MIME]).
	$fbtReturns['no_upload']    = "You must upload an image";
	$fbtReturns['wrong_format'] = "The file you uploaded is a(n) [MIME]. You must upload an image in either JPG or JPEG format.";
	$fbtReturns['too_large']    = "The file you selected to upload is too large. Please limit your image to a maximum of " . $fbtMaxFileSize . " MB.";
	$fbtReturns['success']      = "Congratulations, you're tagged!";
	
	## Tell us about your image
	$fbtIsWatermark      = true;		// Is your image a watermark? (ie: Are we just putting your logo in the bottom right corner?)
	$fbtAestheticOffset  = -10;			// Play with this setting until your overlay image is being applied to your liking (controls placement height/y-value);
										// changing this value is trial-and-error and will most likely have to be changed when the image is changed
	### OVERLAY IMAGE OPTIONS
	$fbtOverlayWidth     = 200;			// Give us your overlay image's width (in pixels)
	$fbtOverlayHeight    = 150;			// Give us your overlay image's height (in pixels)
	$fbtWidthOverride	 = false;		// Comment this block out if you want your overlay to be less than 200px wide
	
	### WATERMARK IMAGE OPTIONS
	$fbtWatermarkWidth	 = 177;			// Give us your watermark image's width (in pixels)
	$fbtWatermarkHeight	 = 122;			// Give us your watermark image's width (in pixels)
	$fbtWatermarkPY		 = "bottom";	// Specify the vertical placement of the watermark: "top" for top, "bottom" for bottom
	$fbtWatermarkPX		 = "right";		// Specify the horizontal placement of the watermark: "left" for left, "right" for right
	
	global $$fbtTextReturn, $$fbtImageReturn;
	
	# Begin Script
	
	$picUpload = $tmpImage['tmp_name'];
	
	if( empty( $picUpload ) ){
		$$fbtTextReturn = $fbtReturns['no_upload'];
		return false;
	}else{
		$getInfo = getimagesize( $picUpload );
	}
	
	if( $tmpImage['size'] > ( $fbtMaxFileSize * 1000000 ) || filesize( $picUpload ) > ( $fbtMaxFileSize * 1000000 ) ){
		$$fbtTextReturn = $fbtReturns['too_large'];
		return false;
	}

	if( $getInfo['mime'] != "image/jpeg" && $getInfo['mime'] != "image/jpg" ){
		$temp = $fbtReturns['wrong_format'];
		$$fbtTextReturn = str_replace( "[MIME]", $tmpImage['type'], $temp );
		return false;
	}
	
	# Verify the overlay image is accounted for and potential script errors are non-existant
	if( empty( $fbtOverlay ) ){
		die( "<h3>FBT Error:</h3> You must specify an overlay image to use (configuration section)." );
	}elseif( !file_exists( $fbtRootPath . $fbtResFolder . $fbtOverlay ) ){
		die( "<h3>FBT Error:</h3> The overlay image/path you specified is not valid. Check your folder and file names then try again." );
	}
	
	# If the resources folder does not end with a slash, add it
	if( substr( $fbtResFolder, -1 ) != "/" )
		$fbtResFolder .= "/";
		
	$overlayInfo = getimagesize( $fbtRootPath . $fbtResFolder . $fbtOverlay );
	if( $overlayInfo['mime'] != "image/png" )
		die( "<h3>FBT Error:</h3> Your overlay image must be in PNG file format" );
	if( $fbtOverlayWidth < 200 && $fbtIsWatermark == false && $fbtWidthOverride == false )
		die( "<h3>FBT Error:</h3> Your overlay width must be 200px wide in order to look appealing." . 
			 "<br /><br />If you wish to override, switch \"&#36;fbtWidthOverride\" to \"true\" in the configuration section." );
	if( !file_exists( $fbtRootPath . $fbtProFolder ) )
		die( "<h3>FBT Error:</h3> The processed folder path you have specified is invalid. Please check it and try again (configuration section)." );
	if( !file_exists( $fbtRootPath . $fbtResFolder . $fbtOopsImage ) )
		die( "<h3>FBT Error:</h3> The \"oops\" image you specified in the configuration section does not exist. Please correct and try again." );
	
	# If in "check mode" return the script
	if( !$goForIt ){ return true; }
	
	# Account for possible directory flaws
	if( substr( $fbtProFolder, -1 ) != "/" )
		$fbtProFolder .= "/";
	if( substr( $fbtRootPath, -1 ) != "/" )
		$fbtRootPath .= "/";
	
	$imgRender = imagecreatefromjpeg( $picUpload );
	
	# FYI: $getInfo[0] = Width, $getInfo[1] = Height of the original image
	# This script locks aspect ratio based on width if it's an overlay, and height if it is a watermark
	
	# Facebook Maximums
	$fbMax[0] = 200;
	$fbMax[1] = 600;
	
	if( $fbtIsWatermark ){
		# You have selected to use a watermark image

		$aspectRatio = $getInfo[1] / $getInfo[0];
		if( $getInfo[0] > $fbMax[0] ){
			$newInfo[0] = $fbMax[0];
		}else{
			$newInfo[0] = $getInfo[0];
		}
		$newInfo[1] = $aspectRatio * $newInfo[0];
		
		$tempImage = imagecreatetruecolor( $newInfo[0], $newInfo[1] );
		imagecopyresampled( $tempImage, $imgRender, 0, 0, 0, 0, $newInfo[0], $newInfo[1], $getInfo[0], $getInfo[1] );
		
		# Alpha blending allows for transparent PNGs to be applied, then load the watermark file
		imagealphablending( $tempImage, true );
		$imgWatermark = imagecreatefrompng( $fbtRootPath . $fbtResFolder . $fbtWatermark );
		
		# Determine which corner the user has specified, then calculate that location
		if( $fbtWatermarkPX == "left" ){
			# Left has been selected
			$dest[0] = 0;
		}else{
			# Right has been selected
			$dest[0] = $newInfo[0] - $fbtWatermarkWidth;
		}
		if( $fbtWatermarkPY == "top" ){
			$dest[1] = 0;
		}else{
			$dest[1] = $newInfo[1] - $fbtWatermarkHeight;
		}
		
		$dest[1] += $fbtAestheticOffset;
		
		# Determine the file. Loop in case of potential duplicate file names
		do{
			$fileName = $fbtRootPath . $fbtProFolder . time() . "-processed.jpg";
		}while( file_exists( $fileName ) );
		
		# Copy the overlay file onto the resized canvas. Then, prepare for display and output
		imagecopy( $tempImage, $imgWatermark, $dest[0], $dest[1], 0, 0, $fbtWatermarkWidth, $fbtWatermarkHeight );
		imagejpeg( $tempImage, $fileName, 100 );
		
		# Prepare file for output
		if( !file_exists( $fileName ) )
			$fileName = $fbtRootPath . $fbtResFolder . $fbtOopsImage;
		
		$$fbtImageReturn = $fileName;
		
		# Free up all the temporary images created on the server during the script run
		imagedestroy( $tempImage );
		imagedestroy( $imgRender );
		imagedestroy( $imgWatermark );
		
		# Return with the good news
		$$fbtTextReturn = $fbtReturns['success'];
		return true;
		
	}else{
		# You have selected to use an overlay image, aspect ratio is locked on width
		
		# Establish aspect ratio based on the fixed overlay width
		$aspectRatio = $getInfo[1] / $getInfo[0];
		$newInfo[0] = $fbtOverlayWidth;
		$newInfo[1] = $aspectRatio * $newInfo[0];
		
		# Determine placement height (y value) based on the new height and the specified offset value
		$placementHeight = $newInfo[1] + $fbtAestheticOffset;
		
		# Create a new, resized canvas and then copy a resized version of the original upload to it
		$tempImage = imagecreatetruecolor( $newInfo[0], ( $newInfo[1] + $fbtOverlayHeight + $fbtAestheticOffset ) );
		imagecopyresampled( $tempImage, $imgRender, 0, 0, 0, 0, $newInfo[0], $newInfo[1], $getInfo[0], $getInfo[1] );
		
		# Alpha blending allows for transparent PNGs to be applied, then load the overlay file
		imagealphablending( $tempImage, true );
		$imgOverlay = imagecreatefrompng( $fbtRootPath . $fbtResFolder . $fbtOverlay );
		
		# Determine the file. Loop in case of potential duplicate file names
		do{
			$fileName = $fbtRootPath . $fbtProFolder . time() . "-processed.jpg";
		}while( file_exists( $fileName ) );
		
		# Copy the overlay file onto the resized canvas. Then, prepare for display and output
		imagecopy( $tempImage, $imgOverlay, 0, $placementHeight, 0, 0, $fbtOverlayWidth, $fbtOverlayHeight );
		imagejpeg( $tempImage, $fileName, 100 );
		
		# Prepare file for output
		if( !file_exists( $fileName ) )
			$fileName = $fbtRootPath . $fbtResFolder . $fbtOopsImage;
		
		$$fbtImageReturn = $fileName;
		
		# Free up all the temporary images created on the server during the script run
		imagedestroy( $tempImage );
		imagedestroy( $imgRender );
		imagedestroy( $imgOverlay );
		
		# Return with the good news
		$$fbtTextReturn = $fbtReturns['success'];
		return true;
	}

}

/* Acta Est Fabula */
?>