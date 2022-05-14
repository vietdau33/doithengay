@extends('admin_layout')
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
                        <label class="toggle" for="system_active">
                            <label class="d-inline-block m-0 mr-3">TRẠNG THÁI KÍCH HOẠT WEBSITE</label>
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
