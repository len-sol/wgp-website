<?php
session_start();

// If user is already logged in, redirect to unverify.php
if (isset($_SESSION['username'])) {
    header("Location: unverify.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Warranty System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white shadow-lg rounded-lg px-8 py-10">
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-bold text-gray-900">Warranty System</h2>
                <p class="text-sm text-gray-600 mt-2">Sign in to access the dashboard</p>
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="mt-4 p-3 text-sm text-red-600 bg-red-100 rounded-lg">' . htmlspecialchars($_SESSION['error']) . '</div>';
                    unset($_SESSION['error']);
                }
                ?>
            </div>

            <form action="login_process.php" method="POST" class="space-y-6">
                <!-- Username Field -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" id="username" name="username" required 
                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm 
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                  placeholder-gray-400"
                           placeholder="Enter your username"
                           autocomplete="username">
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" required 
                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm 
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                  placeholder-gray-400"
                           placeholder="Enter your password"
                           autocomplete="current-password">
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg
                                   shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 
                                   transition duration-200">
                        Sign in
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
