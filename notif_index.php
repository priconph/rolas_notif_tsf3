<?php
require_once('libraries/includes.php');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Notification Mailer</title>
	
	<script>

		//an interval function that loops every 1 minute and run a specific function called send mail
		window.onload=function(){
			return_email_logs();
			
			setInterval( function(){
				return_email_logs();
				console.log('reload');
			},60000);
		}

		function return_email_logs() {
			$.ajax({url:"pages/daily_notif.php",
				success:function(result){
					$('#div_message').append(result);
					console.log(result);
				}
			});
		}

	</script>

  </head>
  
<body>

<!-- do not change this is what the function checks if is false/blank("") the mailer will sent for today and if is true/ has a value on it this will start to go to the "else part" of the sendmail function -->
<div class="container">
	<center>
		
		<h1>DO NOT CLOSE</h1>
		<p>This is the Auto mailer of the TS-F3 ROLAS.</p>
		
		<div id="div_message">
		</div>
	</center>

</div>

</body>
</html>


