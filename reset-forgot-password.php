<?php
require_once __DIR__ . '/classes/ResetForgotPassword.php';

$resetForgotPassword = new ResetForgotPassword();
$resetForgotPassword->handlePasswordReset();

$errors = $resetForgotPassword->getErrors();
$success_message = $resetForgotPassword->getSuccessMessage();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Reset Your Password</h2>
        
        <?php if (isset($errors['token'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p><?php echo $errors['token']; ?></p>
            </div>
        <?php elseif ($success_message): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p><?php echo $success_message; ?></p>
            </div>
        <?php else: ?>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . '?token=' . urlencode($_GET['token'] ?? '')); ?>" novalidate>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">New Password</label>
                    <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <?php if (isset($errors['password'])): ?>
                        <p class="text-red-500 text-xs italic mt-1"><?php echo $errors['password']; ?></p>
                    <?php endif; ?>
                </div>
                <div class="mb-6">
                    <label for="confirm_password" class="block text-gray-700 text-sm font-bold mb-2">Re-enter New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <?php if (isset($errors['confirm_password'])): ?>
                        <p class="text-red-500 text-xs italic mt-1"><?php echo $errors['confirm_password']; ?></p>
                    <?php endif; ?>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                        Reset Password
                    </button>
                </div>
            </form>
        <?php endif; ?>
        
        <?php if (isset($errors['update'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mt-4" role="alert">
                <p><?php echo $errors['update']; ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>