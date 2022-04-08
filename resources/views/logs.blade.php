<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <title>Log Screen</title>
    <link rel="shortcut icon" href="{{ asset('image/logo-trans.png') }}">
    <style>
        body {
            width: 700px;
            max-width: 95%;
            margin: 50px auto auto;
            font-family: "Montserrat", sans-serif;
        }
        .list{
            background: #d9d9d9;
            padding: 12px 20px;
            border-radius: 7px;
            box-shadow: 2px 2px 3px 1px rgba(0, 0, 0, 0.2);
            list-style: decimal;
            font-size: 18px;
        }
        .list li{
            margin-bottom: 5px;
            position: relative;
            left: 20px;
        }
        .title{
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <ul class="list">
        <h2 style="text-align: center; margin-top: 0">NHẬT KÝ THAY ĐỔI</h2>
        <h3 class="title">27/3/2022</h3>
        <li>Đăng ký user</li>
        <li>Đăng nhập user</li>
        <li>Thay đổi thông tin user</li>
        <li>Thay đổi mật khẩu</li>
        <h3 class="title">05/04/2022</h3>
        <p style="font-weight: bold">Lưu ý: Test mua thẻ thì mua mệnh giá 10.000 và 20.000 thôi. Vì account test còn 100k :3</p>
        <li>Đổi thẻ cào</li>
        <li>Lịch sử đổi thẻ cào</li>
        <li>Mua thẻ cào</li>
        <li>Lịch sử mua thẻ cào</li>
        <li>Nạp tiền (chưa phát triển app check tin nhắn nên đang xử lý accept bằng tay ở admin)</li>
        <li>Rút tiền</li>
        <li>Lịch sử rút tiền</li>
        <li>Danh sách thẻ ngân hàng của user</li>
        <li>Thêm mới thẻ ngân hàng</li>
        <h3 class="title">08/04/2022</h3>
        <li>Admin Panel: Tài khoản | Mật khẩu < admin | admin1 ></li>
        <li>Admin Panel: Chấp nhận yêu cầu rút tiền</li>
    </ul>
</body>
</html>
