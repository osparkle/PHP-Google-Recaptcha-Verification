<!-- Prepared by Simeon Adedokun <femsimade@gmail.com> -->

<!-- 
	This page contains the FORM and form submission is also processed on the same page.
-->

<!-- 
READ THE FOLLOWING BEFORE THE FORM

In the v3 Admin Console,

1. To create Recaptcha for a website, visit the following URL: https://www.google.com/recaptcha/admin. Click/touch the + sign at the top-right side to create reCaptcha for a new site,

2. Give the recaptcha a label (maybe name of the website or any name to ID it), 

3. Select reCAPTCHA v2, then Invisible reCAPTCHA badge, under Type, 

4. Add the domain name(s) you want to use the reCAPTCHA (one per line), e.g. simdols.com, orgds.org. Note that all sub-domains running independently must be added. You can add localhost as a domain if you're testing on a localhost, but remember to remove it when the website is in production stage.

5. You can add multiple owners if the reCAPTCHA will be managed by multiple persons

6. Accept ToS

7. Submit


* After a successful submission, your keys will appear. 

* If you're retuning to the reCAPTCHA Admin Consoe, locate your newly added reCaptcha/website at the top of the page. Click on/touch Settings/Cog icon to see your reCaptcha Keys that will be used on the website. The SITE KEY is used at the front end (in a form). while the SECRET KEY is used at the back end for validation.

-->

<?php
// Create variables for reCAPTCHA keys

# public/front-end key. Change the key to yours
define('your_site_key', '6LcTVd0cAAAAAI5J3iVNCTsHYwN1ZUkWDlwYTyVe');

# secret/back-end key. Change the key to yours
define('your_site_secret_key', '6LcTVd0cAAAAAMZXjzvYmrFnDFEr8dMU8CfZEDJ-');

/*
	Here is my verifyRecaptcha function that will verify the user, using the SECRET_KEY
	
	You copy the function to your code for use
*/
function verifyRecaptcha($response = ""){
   	// reCaptcha verification function
   	// Author: Simeon Adedokun <femsimade@gmail.com>
  	$secret = your_site_secret_key;// defined on line 40
  	$remoteip = $_SERVER["REMOTE_ADDR"];
  	$url = "https://www.google.com/recaptcha/api/siteverify";
   	$post_data = array(
     	'secret' => $secret,
     	'response' => $response,
     	'remoteip' => $remoteip
   	);
  	// Curl Request
  	$curl = curl_init();
  	curl_setopt($curl, CURLOPT_URL, $url);
  	curl_setopt($curl, CURLOPT_POST, true);
  	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  	curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
  	$curlData = curl_exec($curl);
  	curl_close($curl);
  
  	$recaptchaResult = json_decode($curlData, true);
  	if (array_key_exists("success", $recaptchaResult) && $recaptchaResult["success"]){
    	// success
    	return true;
  	}else {
  		//No success
  		return false;
   	}
  }


// Process form submission
  if (isset($_POST['check']) && $_POST['check']==1) {
  	// form is being submitted
  	$fullname = $_POST['fullname'];
  	$email = $_POST['email'];
  	$password = $_POST['pass'];
  	
  	// verify user data
  		# your verification here

  	// verify reCaptcha
  	// recaptcha response will be submitted with the form
  	if(isset($_POST["g-recaptcha-response"]) && isset($_POST["usertype"])){
		$response = $_POST["g-recaptcha-response"];
	  	// Parse data
	  	$getStatus = verifyRecaptcha($response);
	  
	  	if ($getStatus){
	   		# user verified

	   	}else {
			# user not verified
			# report error
		}
	}

	// Process form submission if no error
	
  }

?>
<html>

  <head>
    <title>reCAPTCHA Demo: Sample Form</title>

    <!-- Load reCAPTCHA api.js -->
    <!-- 
    The script must be loaded using the HTTPS protocol and can be included from any point on the page without restriction.
     -->
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<script>
		function onSubmit(token) {
			document.getElementById("reg-form").submit();
		}
	</script>
  </head>
  <body>
  	<h2>Register</h2>
    <form id='reg-form' action="?" method="POST">
    	<p>
    		Name:<br>
    		<input type="text" name="fullname">
    	</p>
    	<p>
    		Email:<br>
    		<input type="email" name="email">
    	</p>
    	<p>
    		Password:<br>
    		<input type="password" name="pass">

    		<input type="hidden" name="check" value="1">
    	</p>
    	<p>
    		<!-- This is the aspect where the reCAPTCHA SITE KEY is implemented -->

    		<!-- This page will show "ERROR for site owner: Invalid site key" until you add a valid SITE KEY to replace your_site_key where the variable was difined on line 37 -->
			<button class="g-recaptcha" data-sitekey="<?= your_site_key ?>" data-callback='onSubmit'>Register</button>
		</p>
      <br/>
    </form>
  </body>
</html>