<?php

//assume all inputs are valid and present
$first_name                                 = $_POST['first_name'];
$last_name                                  = $_POST['last_name'];
$email                                      = $_POST['email'];
$phone                                      = $_POST['phone'];
$subject                                    = $_POST['subject'];
$message                                    = $_POST['message'];


// SQL Connection
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "inquiries";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO Inquiry (firstname, lastname, email, phone, phone, subject, message)
VALUES ('$first_name', '$last_name', '$email', '$phone', '$subject', '$message')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

//start email
ob_start();
?>
<html>
	<head>
		<title><?=$subject?></title>
		<style type="text/css">
			td {
				font: 12px Arial, Verdana, Helvetica, sans-serif;
				border-bottom: 1px solid #D2D5DA;
				color: #000;
				padding: 5px 10px;	
			}
			table { 
				margin: 10px 0; 
				border-collapse: separate; 
				border: 1px solid #D2D5DA; 
			}
		</style>
	</head>
	<body>
		<table width="800" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td width="20%" valign="top">
                    <b>Sent on: </b>
                </td>
				<td valign="top">
                    <?=date('l, M jS Y g:i a')?>
                </td>
			</tr>
			<?
			foreach($_POST as $key => $val) {?>
				<tr>
					<td width="20%" valign="top">
                        <b><?=ucwords(strtolower(str_replace("_", " ", $key)))?></b>: 
                    </td>
					<td valign="top">
                        <?=$val?>
                    </td>
				</tr>
			<?}?>
		</table>
	</body>
</html>
<?
$mssg = ob_get_contents();
ob_end_clean();

$mail                                       = new phpmailer();
$mail->IsSendmail();
$mail->AddReplyTo($email, $full_name);
$mail->SetFrom($email, $full_name);

$mail->ContentType                          = "text/html";
$mail->Subject                              = $subject;
$mail->Body                                 = $mssg;
$mail->AltBody                              = $frm->filter($mssg, 'nohtml');
$mail->WordWrap                             = 80;
$mail->SingleTo                             = true;
$mail->AddAddress("cconc023@fiu.edu");

if(!$mail->Send()) die('Mailer error: ' . $mail->ErrorInfo);
$mail->ClearAddresses();

$reload                                     = "index.html";
header("Location: $reload");

?>