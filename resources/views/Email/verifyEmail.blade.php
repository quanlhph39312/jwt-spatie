<!doctype html>
<html lang="en">
<head>
    <title>Verify EMail</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f8f9fa;">
    <div style="display: flex; justify-content: center; padding: 20px;">
        <div style="width: 100%; max-width: 600px; padding: 20px; background-color: #ffffff; border: 1px solid #dee2e6;">
            <h3 style="color: #333333;">Xin Chào! {{$account->name}}</h3>
            <p style="color: #333333;">Quý khách hoặc ai đó đã sử dụng địa chỉ email này để thực hiện đăng ký tài khoản tại website ABC. Để xác thực tài khoản xin vui lòng bấm vào nút bên dưới</p>
            <div style="text-align: center; margin: 20px 0;">
                <a href="{{route('verifyEmail', $account->email)}}" style="display: inline-block; padding: 10px 20px; color: #ffffff; background-color: #6c757d; text-decoration: none; border-radius: 5px;">Xác thực tài khoản</a>
            </div>
            <p style="color: #333333;">Nếu bạn không thực hiện đăng ký tài khoản, xin vui lòng bỏ qua email này!</p>
            <p>
                <span style="font-weight: bold; color: #333333;">Trân trọng,</span><br>
                <span style="font-weight: bold; color: #dc3545;">Bố của các con trai</span>
            </p>
            <hr style="border: none; border-top: 1px solid #dee2e6;">
            <small style="color: #6c757d;">
                Nếu bạn gặp vấn đề trong việc xác thực tài khoản, vui lòng bấm vào link dưới đây để được hỗ trợ:
                <a href="http://127.0.0.1:5501/index.html" style="color: #007bff; text-decoration: none;">http://127.0.0.1:5501/index.html</a>
            </small>
        </div>
    </div>
</body>
</html>
