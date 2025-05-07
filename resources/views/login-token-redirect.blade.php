<!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
</head>
<body>
    <p>Logging you in...</p>
    <script>
        const token = sessionStorage.getItem('api_token');
        if (token) {
            window.location.href = `/complete-monitoring/public/login-with-token-check?token=${token}`;
        } else {
            window.location.href = `/complete-monitoring/public/login`;
        }
    </script>
</body>
</html>
