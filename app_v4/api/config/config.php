<?php
	
	$conn = mysqli_connect("localhost","experted_radius2","experted_radius2","experted_radius");
	
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	else{
//		echo "Done";
	}
	
	$baseurl = "index.php";
// 	$base_url = "http://localhost:8080/drydelights/";
	$pro_url = "https://grihasthiudyog.com/backend/product/";
	$cat_url = "https://grihasthiudyog.com/backend/product/";
	
	# Firebase Credentials
	$firebase_url = "https://fcm.googleapis.com/fcm/send";
	$firebase_key = "AAAAgPgsgqg:APA91bF-2WnZUkgaTQPezVSHF7cQHwCRnqxZgkQ3K8aRadtEs3eOuJempPSoF1EygJeon6853ssEK6G5F1HXHF5laGVIbE4M9ChT5hiwCze_wl9aZTsxvYRhN4zzkpJwA3qP9kckbMiv";
?>
