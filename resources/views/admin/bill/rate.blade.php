@extends('admin_layout')
@section('contents')
    <div class="content">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title font-weight-bold">Chiết khấu gạch cước</h3>
            </div>
            <div class="block-content">
                @php($configBill = config('bill'))
                <div class="table-responsive">
                    <form action="{{ route('admin.feature.discount_bill.post') }}" method="POST" onsubmit="return ChangeRateBill.submit(this)">
                        @csrf
                        <table class="table table-striped table-vcenter text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Loại cước</th>
                                    <th>Loại tài khoản</th>
                                    <th>Chiết khấu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($stt = 1)
                                @foreach($rates as $key => $rate)
                                    @foreach($rate as $k => $r)
                                        <tr>
                                            <th>{{ $stt++ }}</th>
                                            <td>{{ $configBill[$key]['text'] }}</td>
                                            <td>{{ get_text_type_account_bill($k) }}</td>
                                            <td>
                                                <input
                                                    type="text"
                                                    name="{{ $r[0]['name'] }}"
                                                    class="form-control m-auto text-center"
                                                    value="{{ $r[0]['rate_use'] }}"
                                                    style="width: 100px"
                                                    autocomplete="off"
                                                />
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-right">
                            <button class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
                <hr />
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        window.ChangeRateBill = {
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
                        let err = $('<li />').html(result.errors[i]);
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
