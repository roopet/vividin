<?php
/* Set e-mail recipient */
$myemail = 'roope.tahvanainen@gmail.com'. ', '; // note the comma
$myemail .= 'roope@vividin.fi';



$name = check_input($_POST['name'], "Please remember to give us your name ");
/*$subject = check_input($_POST['aihe'], "Mikä on palautteen aihe?"); */
$email = check_input($_POST['email']);
$subject = check_input($_POST['subject'], "Please don´t forget about the subject");
$message = check_input($_POST['message'], "Please don´t forget the message");


if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email))
{
show_error("Please check the spelling of your email");
}

$subject = $subject;

$message = "

Nimi: $name
E-mail: $email
Aihe: $subject

Palaute:
$message

";


mail($myemail, $subject, $message);


header('Location: thankyou.htm');
exit();


function check_input($data, $problem='')
{
$data = trim($data);
$data = stripslashes($data);
$data = htmlspecialchars($data);
if ($problem && strlen($data) == 0)
{
show_error($problem);
}
return $data;
}

function show_error($myError)
{
?>
<html>
<head>

<script>
function goBack() {
    window.history.back()
}
</script>
</head>
<body>

<p>There was a problem:</p>
<strong><?php echo $myError; ?></strong>
<p>Please press return and try again</p>
<button onclick="goBack()">Return</button>
</body>
</html>
<?php
exit();
}
?>