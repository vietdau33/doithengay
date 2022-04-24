@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Kết nối API</p>
        </div>
        <div class="box-content box-pay-card p-3 mt-4" style="max-width: initial">
            <div class="container">
                <div class="form-group d-flex">
                    <h5 class="font-weight-bold m-0">Key của bạn: </h5>
                    <h5 class="font-weight-bold m-0 ml-2" style="color: #c0201e">{{ $api_key != '' ? $api_key : 'Vui lòng đăng nhập để lấy hoặc liên hệ ADMIN' }}</h5>
                </div>
                <hr />
                <div class="alert border-info">
                    <p class="mb-2"><b>URL nhận dữ liệu:</b> {{ url('/api/gach-the') }}</p>
                    <p class="mb-2" style="word-break: break-all"><b>URL mẫu:</b> {{ url('/api/gach-the') }}?telco=VIETTEL&code=123456&serial=123456&type=fast&amount=100000&request_id=12345&callback=https://domain.com/callback&api_key=abc1234</p>
                    <p class="mb-2"><b>Phương thức:</b> GET</p>
                    <p class="mb-2"><b>Danh sách tham số gửi lên:</b></p>
                    <ul class="ml-5 mb-2" style="list-style: decimal">
                        <li><b>telco:</b> VIETTEL, VINAPHONE, MOBIFONE, VIETNAMOBILE</li>
                        <li><b>code:</b> Mã thẻ cào</li>
                        <li><b>serial:</b> Số seri thẻ cào</li>
                        <li><b>type:</b> Loại gạch thẻ (fast = nhanh, slow = chậm)</li>
                        <li><b>amount:</b> Mệnh giá (quy ước mệnh giá thực : 10000, 20000,...)</li>
                        <li><b>request_id:</b> Mã request mà bạn gửi lên hệ thống</li>
                        <li><b>callback:</b> URL nhận kết quả gạch thẻ</li>
                        <li><b>api_key:</b> API KEY kết nối mà hệ thống cung cấp cho bạn (lưu ý: không tiết lộ key này cho bất kỳ ai)</li>
                    </ul>
                    <p class="mb-2"><b>Tham số hệ thống trả về:</b></p>
                    <ul class="ml-5 mb-2" style="list-style: decimal">
                        <li><b>success:</b> 1 - Thành công, 0 - Thất bại</li>
                        <li><b>message:</b> Message tương ứng</li>
                        <li><b>hash:</b> Mã hash của lệnh gạch thẻ. Dùng để kiểm tra kết quả gạch thẻ!</li>
                    </ul>
                    <p class="mb-2"><b>Tham số link callback trả về:</b></p>
                    <ul class="ml-5 mb-2" style="list-style: decimal">
                        <li><b>hash:</b> Mã hash trả về lúc gủi yêu cầu gạch thẻ</li>
                        <li><b>code:</b> Mã thẻ cào đã gửi lên</li>
                        <li><b>serial:</b> Số seri thẻ cào đã gửi lên</li>
                        <li><b>success:</b> Trạng thái gạch thẻ (1 - thành công, 0 - thất bại)</li>
                        <li><b>message:</b> Message theo trạng thái</li>
                        <li><b>amount:</b> Tiền nhận được</li>
                        <li><b>request_id:</b> Mã request mà bạn gửi lên hệ thống</li>
                        <li><b>declared_value:</b> Mệnh giá mà bạn gửi lên hệ thống</li>
                        <li><b>card_value:</b> Mệnh giá thực của thẻ. Bằng 0 nếu gạch thẻ lỗi</li>
                    </ul>
                    <div class="alert alert-warning">
                        <h5 class="mb-2 font-weight-bold">Note:</h5>
                        <ul class="ml-5" style="list-style: decimal">
                            <li>Nếu bạn gặp khó khăn trong việc kết nối API, có thể liên hệ admin để được tiến hành hỗ trợ kỹ thuật miễn phí !</li>
                            <li>Nếu hệ thống của bạn phức tạp, có thể admin sẽ thu phí kỹ thuật để phục vụ bạn tốt hơn</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
