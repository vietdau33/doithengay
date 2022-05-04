@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Cài đặt bảo mật</p>
        </div>
        <div class="body-content">
            <div class="box-content text-left p-3 mt-4">
                <h5 class="font-weight-bold">Xác thực 2 lớp</h5>
                <div class="form-group">
                    <label class="toggle" for="security_level_2">
                        <span class="d-inline-block mr-2">Trạng thái kích hoạt bảo mật 2 lớp</span>
                        <input type="checkbox" class="toggle__input" id="security_level_2" {{ user()->security_level_2 === 1 ? 'checked' : '' }} />
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
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('#security_level_2').on('change', function(e){
            e.preventDefault();
            const data = {
                security_level_2 : $(this).prop('checked')
            }
            Request.ajax('/security/setting/security_level_2', data, function(result){
                alert(result.message);
            });
        });
    </script>
@endsection
