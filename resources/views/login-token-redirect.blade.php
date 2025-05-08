<!DOCTYPE html>
<html>
<head>
    <style>
        .loader {
                position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background: url("{{ asset('/img/3.gif')}}") 50% 50% no-repeat rgb(249,249,249) ;
                opacity: .8;
                background-size:200px 120px;
            }
    </style>
</head>
<body>
    <div class="loader"></div>
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
