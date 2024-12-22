<?php
require 'phpmailer/phpmailer/src/PHPMailer.php';
require 'phpmailer/phpmailer/src/SMTP.php';
require 'phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true); 
$verificationCode = rand(100000, 999999); // Generate the code globally for both email sending and validation

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Password validation server-side (optional but recommended)
        if (strlen($password) < 12 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            echo "<div id='errorMessage'>Password does not meet the required criteria.</div>";
            return;
        }

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'testingoconnel@gmail.com'; // Your email
            $mail->Password   = ''; // Your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
        
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );            

            $mail->setFrom('testingoconnel@gmail.com', 'Testing');
            $mail->addAddress($email, $username); 

            $mail->isHTML(true); 
            $mail->Subject = 'Verification Code';
            $mail->Body    = "Your verification code is: <b>{$verificationCode}</b>";
            $mail->AltBody = "Your verification code is: {$verificationCode}";

            $mail->send();
            echo "<div id='successMessage'><i class='fas fa-check-circle'></i> Verification code sent. Check your email.</div>";
        } catch (Exception $e) {
            echo "<div id='errorMessage'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</div>";
        }
    } else {
        echo "<div id='errorMessage'>All fields are required.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-image: url('background2.jpg');
            font-family: Arial, sans-serif;
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            transition: background 1.5s ease-in-out; 
        }

        .signup-container {
            max-width: 290px;
            width: 50%;
            height: 80%;
            padding: 30px;
            background-color: rgba(125, 67, 138, 0.95);
            border-radius: 20px;
            box-shadow: 0 4px 8px rgb(110, 46, 103);
            text-align: center;
            color: #fff;
            opacity: 1;
            transform: scale(1);
            transition: opacity 0.8s ease, transform 0.8s ease; 
        }

        .signup-container img {
            width: 50px;
            margin-bottom: 5px;
        }
        .signup-label {
            margin-top: 5px;
            margin-bottom: 5px;
            font-size: 18px;
            font-weight: bold;
            color: white;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
            text-align: left;
            transition: transform 0.3s ease; 
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 20px;
            border: none;
            border-radius: 20px;
            outline: none;
            background-color: #f1f1f1;
            color: #333333;
            box-sizing: border-box;
        }

        .input-group:hover {
            transform: scale(1.05);
        }

        .input-group .fa-user,
        .input-group .fa-eye,
        .input-group .fa-envelope {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            color: #333;
        }

        button[type="submit"]:hover {
            transform: scale(1.05);
        }

        p {
            font-size: 14px;
            color: #333; 
        }

        a {
            color: darkblue;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 20px;
            background-color: rgb(71, 93, 219);
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #1cfd03;
            transform: scale(1.05); 
        }
        .success-message {
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
            color: green;
            background-color: #e0f7e9;
            border: 1px solid #d0f0c0;
            border-radius: 10px;
            padding: 10px;
            text-align: center; /* Center the text and icon */
            display: flex;
            align-items: center;
            justify-content: center; /* Center the content horizontally */
        }

        .success-message i {
            margin-right: 10px;
        }


        #passwordRequirements {
            margin-top: 10px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
        }

        #passwordRequirements ul {
            list-style-type: none;
            padding: 0;
        }

        #passwordRequirements li {
            color: rgb(237, 235, 235);
            margin: 5px 0;
        }

        #passwordRequirements li.valid {
            color: green;
        }

        #passwordRequirements li::before {
            content: "• ";
        }

        #successMessage, #errorMessage {
            display: none;
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }

        #successMessage {
            color: green;
            background-color: #e0f7e9;
            border: 1px solid #d0f0c0;
            border-radius: 10px;
            padding: 10px;
        }

        #errorMessage {
            color: red;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 10px;
            padding: 10px;
        }
        #passwordRequirements li.valid {
            color: green;
        }


        @media (max-width: 768px) {
            .signup-container {
                max-width: 90%;
                padding: 15px;
            }
            button {
                font-size: 14px;
            }
        }

        body.loaded .signup-container {
            opacity: 1;
            transform: scale(1);
        }
        .hidden { display: none; } /* Hide elements by default */
        #verificationCode { display: none; }
        #resendButton, #submitButton { display: none; }
        #wrongCodeMessage { display: none; color: red; }
    </style>
</head>
<body> 
    <div class="signup-container">
        <img src="Profile2.jpg" alt="Profile Icon">
        <div class="signup-label">Sign-Up</div>
        <form id="signupForm" action="" method="POST">
            <div class="input-group">
                <input type="text" id="email" name="email" required placeholder="Email">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="input-group">
                <input type="text" id="username" name="username" required placeholder="Username">
                <i class="fas fa-user"></i>
            </div>
            <div class="input-group">
                <input type="password" id="password" name="password" required placeholder="Password">
                <i class="fas fa-eye eye-icon" id="togglePassword"></i>
            </div>
            <div id="passwordRequirements">
                <p style="font-size: 10px;">PASSWORD REQUIREMENTS:</p>
                <ul>
                    <li id="length">At least 12 characters</li>
                    <li id="uppercase">At least one uppercase letter</li>
                    <li id="lowercase">At least one lowercase letter</li>
                    <li id="number">At least one number</li>
                    <li id="special">At least one symbol character</li>
                </ul>
            </div>
            <button type="submit">Sign Up</button>
        </form>

        <!-- Verification code section (initially hidden) -->
        <div id="verificationSection">
            <div id="verificationCode" class="input-group hidden">
                <input type="text" id="codeInput" name="codeInput" placeholder="Enter Verification Code">
            </div>
            <button id="submitButton" class="hidden">Submit</button>
            <button id="resendButton" class="hidden">Resend</button>
            <div id="wrongCodeMessage">Verification code wrong.</div>
        </div>
        <p>Already have an account? <a href="admin_login.php">LogIn here</a>.</p>
    </div>

    <script>
        const verificationCode = "<?php echo $verificationCode; ?>"; // Pass PHP code to JavaScript
        const codeInput = document.getElementById('codeInput');
        const submitButton = document.getElementById('submitButton');
        const resendButton = document.getElementById('resendButton');
        const verificationCodeDiv = document.getElementById('verificationCode');
        const wrongCodeMessage = document.getElementById('wrongCodeMessage');

        // After the success message is shown, display verification input
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                verificationCodeDiv.style.display = 'block';
                submitButton.style.display = 'block';
                resendButton.style.display = 'block';
            }
        });

        // Submit verification code
        submitButton.addEventListener('click', function() {
            if (codeInput.value === verificationCode) {
                alert('Code verified successfully!');
                // Redirect to the login form after successful verification
                window.location.href = 'admin_login.php'; // Update with the actual path to your login form
            } else {
                wrongCodeMessage.style.display = 'block';
            }
        });


        // Resend code (reloads the page to trigger new PHP code)
        resendButton.addEventListener('click', function() {
            location.reload(); // Can be replaced with AJAX if preferred
        });
        document.addEventListener('DOMContentLoaded', function() {
            document.body.classList.add('loaded');
        });

        const passwordInput = document.getElementById('password');
        const lengthRequirement = document.getElementById('length');
        const uppercaseRequirement = document.getElementById('uppercase');
        const lowercaseRequirement = document.getElementById('lowercase');
        const numberRequirement = document.getElementById('number');
        const specialRequirement = document.getElementById('special');

        passwordInput.addEventListener('input', function () {
        const passwordValue = passwordInput.value;

        // Check if the password is at least 12 characters long
        if (passwordValue.length >= 12) {
            lengthRequirement.classList.add('valid');
        } else {
            lengthRequirement.classList.remove('valid');
        }

        // Check if the password contains at least one uppercase letter
        if (/[A-Z]/.test(passwordValue)) {
            uppercaseRequirement.classList.add('valid');
        } else {
            uppercaseRequirement.classList.remove('valid');
        }

        // Check if the password contains at least one lowercase letter
        if (/[a-z]/.test(passwordValue)) {
            lowercaseRequirement.classList.add('valid');
        } else {
            lowercaseRequirement.classList.remove('valid');
        }

        // Check if the password contains at least one number
        if (/[0-9]/.test(passwordValue)) {
            numberRequirement.classList.add('valid');
        } else {
            numberRequirement.classList.remove('valid');
        }

        // Check if the password contains at least one special character
        if (/[!@#$%^&*(),.?":{}|<>]/.test(passwordValue)) {
            specialRequirement.classList.add('valid');
        } else {
            specialRequirement.classList.remove('valid');
        }
        });
    </script>
</body>
</html>