@extends('admin_layout')
@section('contents')
    <div class="content">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title font-weight-bold">Chiết khấu đổi thẻ</h3>
            </div>
            <div class="block-content">
                @foreach($rates as $key => $rate)
                    <div class="table-responsive">
                        <h3>{{ ucfirst($key) }}</h3>
                        <form action="{{ route('admin.feature.discount_buy.post', ['name' => $key]) }}" method="POST" onsubmit="return ChangeRateCard.submit(this)">
                            @csrf
                            <table class="table table-striped table-vcenter text-center">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mệnh giá</th>
                                    <th>Bán nhanh</th>
                                    <th>Bán chậm</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($stt = 1)
                                @foreach($rate as $r)
                                    <tr>
                                        <th>{{ $stt++ }}</th>
                                        <td>{{ number_format($r['price']) }}</td>
                                        <td>
                                            <input
                                                type="text"
                                                name="rate_{{ $r['price'] }}"
                                                class="form-control m-auto text-center"
                                                value="{{ $r['rate'] }}"
                                                style="width: 100px"
                                                autocomplete="nope"
                                            />
                                        </td>
                                        <td>
                                            <input
                                                type="text"
                                                name="slow_{{ $r['price'] }}"
                                                class="form-control m-auto text-center"
                                                value="{{ $r['rate_slow'] }}"
                                                style="width: 100px"
                                                autocomplete="nope"
                                            />
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="text-right">
                                <button class="btn btn-primary">Lưu thay đổi</button>
                            </div>
                        </form>
                    </div>
                    <hr />
                @endforeach
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
