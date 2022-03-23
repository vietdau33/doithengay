<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel 8 Phone Number OTP Auth Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 550px">
    <div class="alert alert-danger" id="error" style="display: none;"></div>
    <h3>Add Phone Number</h3>
    <div class="alert alert-success" id="successAuth" style="display: none;"></div>
    <form>
        <label>Phone Number:</label>
        <input type="text" id="number" class="form-control" placeholder="+91 ********">
        <div id="recaptcha-container"></div>
        <button type="button" class="btn btn-primary mt-3" onclick="sendOTP();">Send OTP</button>
    </form>

    <div class="mb-5 mt-5">
        <h3>Add verification code</h3>
        <div class="alert alert-success" id="successOtpAuth" style="display: none;"></div>
        <form>
            <input type="text" id="verification" class="form-control" placeholder="Verification code">
            <button type="button" class="btn btn-danger mt-3" onclick="verify()">Verify code</button>
        </form>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>

<script>
    var firebaseConfig = {
        apiKey: "AIzaSyAlRaAp0MJZVB9HbUSvFBqKEgX7vuk5PbA",
        authDomain: "quickstart-1569373257441.firebaseapp.com",
        databaseURL: "https://quickstart-1569373257441.firebaseio.com",
        projectId: "quickstart-1569373257441",
        storageBucket: "quickstart-1569373257441.appspot.com",
        messagingSenderId: "312879544720",
        appId: "1:312879544720:web:56b5124839634881309b15",
        measurementId: "G-1TVZ0K26BQ"
    };
    firebase.initializeApp(firebaseConfig);
</script>
<script type="text/javascript">
    window.onload = function () {
        render();
    };
    function render() {
        window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
        recaptchaVerifier.render();
    }
    function sendOTP() {
        var number = $("#number").val();
        firebase.auth().signInWithPhoneNumber(number, window.recaptchaVerifier).then(function (confirmationResult) {
            window.confirmationResult = confirmationResult;
            coderesult = confirmationResult;
            console.log(coderesult);
            $("#successAuth").text("Message sent");
            $("#successAuth").show();
        }).catch(function (error) {
            $("#error").text(error.message);
            $("#error").show();
        });
    }
    function verify() {
        var code = $("#verification").val();
        coderesult.confirm(code).then(function (result) {
            var user = result.user;
            console.log(user);
            $("#successOtpAuth").text("Auth is successful");
            $("#successOtpAuth").show();
        }).catch(function (error) {
            $("#error").text(error.message);
            $("#error").show();
        });
    }
</script>
</body>
</html>
