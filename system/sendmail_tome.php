<?php
    // if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //     # FIX: Replace this email with recipient email
    //     $mail_to = "bo.chutipas_st@tni.ac.th";
        
    //     # Sender Data
    //     $name = $_POST["Fname"]." ".$POST["Lname"];
    //     $subject = "From Contact us page: ".$name;
    //     $email = filter_var(trim($_POST["Email"]), FILTER_SANITIZE_EMAIL);
    //     $phone = trim($_POST["Tel"]);
    //     $message = trim($_POST["Comment"]);

    //     if ( empty($name) OR !filter_var($email, FILTER_VALIDATE_EMAIL) OR empty($phone) OR empty($subject) OR empty($message)) {
    //         # Set a 400 (bad request) response code and exit.
    //         http_response_code(400);
    //         echo "Please complete the form and try again.";
    //         exit;
    //     }
        
    //     # Mail Content
    //     $content = "Name: $name\n";
    //     $content .= "Email: $email\n\n";
    //     $content .= "Phone: $phone\n";
    //     $content .= "Message:\n$message\n";

    //     # email headers.
    //     $headers = "From: $name &lt;$email&gt;";

    //     # Send the email.
    //     $success = mail($mail_to, $subject, $content, $headers);
    //     if ($success) {
    //         # Set a 200 (okay) response code.
    //         http_response_code(200);
    //         echo "ok";
    //     } else {
    //         # Set a 500 (internal server error) response code.
    //         http_response_code(500);
    //         echo "Oops! Something went wrong, we couldn't send your message.";
    //     }

    //     } else {
    //         # Not a POST request, set a 403 (forbidden) response code.
    //         http_response_code(403);
    //         echo "There was a problem with your submission, please try again.";
    //     }

    if($_POST['Fname']!= "" && $_POST['Lname']!= "" && $_POST['Tel']!= "" && $_POST['Comment']!= "" && $_POST['Email']!= ""){
        $to = "bo.chutipas_st@tni.ac.th"; // this is your Email address
        $from = $_POST['Email']; // this is the sender's Email address
        $first_name = $_POST['Fname'];
        $last_name = $_POST['Lname'];
        $subject = "Form Contact page - Tent's Hotel";
        $phone = $_POST["Tel"];

        $message = "Name: ".$first_name . " " . $last_name."\n";
        $message .= "Email: ".$from."\n\n";
        $message .= "Phone: ".$phone."\n";
        $message .= "Message:\n".$_POST['Comment']."\n";
        $headers = "From:" . $from;

        mail($to,$subject,$message,$headers);
        $success = 1;
    }else{
        $success = 0;
    }
    echo json_encode($check_sendmail = array('success' => $success ));
    // You can also use header('Location: thank_you.php'); to redirect to another page.
    // You cannot use header and echo together. It's one or the other.
?>