<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MedEquip</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="icon-container">
                    <i class="fa-solid fa-heart-pulse"></i>
                </div>
                <h1>MedEquip</h1>
                <p>Sign in to continue</p>
            </div>

            <div class="login-body">
                <div id="loginError" class="alert-custom" style="display:none;">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span id="loginErrorText"></span>
                </div>

                <form id="loginForm">
                    <div class="input-group-custom">
                        <div class="input-icon">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <input type="email" id="email" name="email" class="form-control-custom"
                            placeholder="Enter your email" required autocomplete="email">
                    </div>

                    <div class="input-group-custom">
                        <div class="input-icon">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" id="password" name="password" class="form-control-custom"
                            placeholder="Enter your password" required autocomplete="current-password">
                        <button type="button" class="eye-button" id="togglePassword">
                            <i id="eyeIcon" class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        Sign In
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="assets/js/login.js"></script>
</body>

</html>