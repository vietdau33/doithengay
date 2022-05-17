@extends('admin_layout')
@section('style')
    <style>
        .block-content .form-group {
            position: relative;
            margin-bottom: 30px;
        }
        .block-content .form-group:after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 0;
            width: 100%;
            height: 0;
            border-bottom: 1px solid #d4d4d4;
            box-shadow: 1px 1px 4px 1px rgba(212, 212, 212, 0.3);
        }
        .block-content .form-group label {
            font-size: 16px;
        }
    </style>
@endsection
@section('contents')
    <div class="content">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title font-weight-bold">Cài đặt hệ thống</h3>
            </div>
            <div class="block-content">
                <form action="" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="api_key_365">API KEY 365</label>
                        <input
                            type="text"
                            class="form-control"
                            id="api_key_365"
                            name="api_key_365"
                            autocomplete="off"
                            placeholder="Ex: {{ md5(now()) }}"
                            value="{{ $settings['api_key_365'] ?? '' }}"
                        />
                    </div>
                    <div class="form-group">
                        <label class="toggle justify-content-start flex-wrap" for="system_active">
                            <label class="d-block w-100 m-0 mr-3">Trạng thái kích hoạt website (Trạng thái tắt sẽ có hiệu lực khi user đăng nhập!)</label>
                            <input type="hidden" name="system_active" value="{{ $settings['system_active'] ?? 0 }}">
                            <input
                                type="checkbox"
                                class="toggle__input"
                                id="system_active"
                                onchange="document.querySelector('{{ '[name="system_active"]' }}').value = this.checked ? '1' : '0'"
                                {{ ($settings['system_active'] ?? 0) == 1 ? 'checked' : '' }}
                            />
                            <span class="toggle-track">
                                <span class="toggle-indicator">
                                    <span class="checkMark">
                                        <svg viewBox="0 0 24 24" id="ghq-svg-check" role="presentation" aria-hidden="true">
                                            <path d="M9.86 18a1 1 0 01-.73-.32l-4.86-5.17a1.001 1.001 0 011.46-1.37l4.12 4.39 8.41-9.2a1 1 0 111.48 1.34l-9.14 10a1 1 0 01-.73.33h-.01z"></path>
                                        </svg>
                                    </span>
                                </span>
                            </span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="api_key_365">Dấu phân cách giữa các thông báo ở màn hình chính</label>
                        <input
                            type="text"
                            class="form-control"
                            id="separator_notification"
                            name="separator_notification"
                            autocomplete="off"
                            value="{{ $settings['separator_notification'] ?? '' }}"
                        />
                    </div>
                    <div class="form-group">
                        <h5 class="mb-5">Cài đặt chuyển tiền</h5>
                        <div class="pl-30">
                            <div class="mb-2 setting-group">
                                <label for="transfer_fee_fix">Phí cố định (Đơn vị Đồng)</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="transfer_fee_fix"
                                    name="transfer_fee_fix"
                                    autocomplete="off"
                                    value="{{ $settings['transfer_fee_fix'] ?? '' }}"
                                />
                            </div>
                            <div class="mb-2 setting-group">
                                <label for="transfer_fee">Phí chuyển tiền (Đơn vị %)</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="transfer_fee"
                                    name="transfer_fee"
                                    autocomplete="off"
                                    value="{{ $settings['transfer_fee'] ?? '' }}"
                                />
                            </div>
                            <div class="mb-2 setting-group">
                                <label for="transfer_turns_on_day">Số lần chuyển trong ngày (Nếu không giới hạn, hãy để giá trị là 00)</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="transfer_turns_on_day"
                                    name="transfer_turns_on_day"
                                    autocomplete="off"
                                    value="{{ $settings['transfer_turns_on_day'] ?? '' }}"
                                />
                            </div>
                            <div class="mb-2 setting-group">
                                <label for="transfer_money_min">Số tiền chuyển tối thiểu (Đơn vị Đồng)</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="transfer_money_min"
                                    name="transfer_money_min"
                                    autocomplete="off"
                                    value="{{ $settings['transfer_money_min'] ?? '' }}"
                                />
                            </div>
                            <div class="mb-2 setting-group">
                                <label for="transfer_money_max">Số tiền chuyển tối đa (Đơn vị Đồng)</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="transfer_money_max"
                                    name="transfer_money_max"
                                    autocomplete="off"
                                    value="{{ $settings['transfer_money_max'] ?? '' }}"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="text-right mb-3 mt-3">
                        <button class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        window.ChangeRateCard = {
            submit : function(el){
                let _form = new FormData(el);
                let _url = el.getAttribute('action');
                Request.ajax(_url, _form, function(result){
                    if(result.success) {
                        alertify.alert('Notification', result.message)
                        $('.alertify .ajs-header').addClass('alert-primary');
                        return false;
                    }

                    let elError = $('<ul />').addClass('list-style-decimal');
                    for(let i = 0; i < result.errors.length; i++){
                        let err = $('<li />').text(result.errors[i]);
                        elError.append(err);
                    }

                    alertify.alert('Error', elError.prop('outerHTML'))
                    $('.alertify .ajs-header').addClass('alert-danger');
                    return false;
                });
                return false;
            }
        }
    </script>
@endsection
