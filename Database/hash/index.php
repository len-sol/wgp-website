<?php
require_once("../authenticate.php");
require_once("../db_connection.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($username) || empty($password)) {
        $message = "<div class='message'>Both username and password are required.</div>";
    } else {
        try {
            // Use a prepared statement to insert data
            $sql = "INSERT INTO login (username, password) VALUES (:username, :password)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            
            $message = "<div class='message success'>User added successfully!</div>";
            
        } catch (PDOException $e) {
            error_log("Error Adding User: " . $e->getMessage());
            $message = "<div class='message'>There was an error adding the user. Please try again later.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            max-width: 400px;
            width: 90%;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .form-container h1 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
            font-size: 1.8rem;
        }

        .form-field {
            margin-bottom: 1.2rem;
        }

        .form-field label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #555;
        }

        .form-field input {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e1e1e1;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .form-field input:focus {
            outline: none;
            border-color: #000;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
        }

        .btn-submit {
            background: #000;
            color: #fff;
            border: none;
            padding: 1rem;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            background: #333;
            transform: translateY(-1px);
        }

        .message {
            margin-top: 1rem;
            text-align: center;
            padding: 0.8rem;
            border-radius: 4px;
            font-size: 0.9rem;
            color: #d9534f;
            background: rgba(217, 83, 79, 0.1);
        }

        .message.success {
            color: #5cb85c;
            background: rgba(92, 184, 92, 0.1);
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .form-field input:invalid:focus {
            animation: shake 0.3s;
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .form-container {
                padding: 1.5rem;
            }

            .form-container h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="form-container animate__animated animate__fadeIn">
        <h1>Add New User</h1>
        <form action="" method="POST" autocomplete="off">
            <div class="form-field">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required 
                       placeholder="Enter username" minlength="3">
            </div>
            <div class="form-field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required 
                       placeholder="Enter password" minlength="3">
            </div>
            <button type="submit" class="btn-submit">Add User</button>
        </form>
        <?php echo $message; ?>
    </div>

    <script>
        // Add animation to form fields when focused
        document.querySelectorAll('.form-field input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
                this.parentElement.style.transition = 'transform 0.3s ease';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });

        // Add subtle animation to the submit button on hover
        const submitBtn = document.querySelector('.btn-submit');
        submitBtn.addEventListener('mouseover', function() {
            this.style.transform = 'translateY(-2px)';
        });
        submitBtn.addEventListener('mouseout', function() {
            this.style.transform = 'translateY(0)';
        });
    </script>
</body>
</html>
