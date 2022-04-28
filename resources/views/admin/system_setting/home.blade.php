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
