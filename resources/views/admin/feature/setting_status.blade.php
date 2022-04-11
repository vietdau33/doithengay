@extends('admin_layout')
@section('contents')
    <div class="content">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title font-weight-bold">
                    Quản lý trạng thái {{ $type == 'buy' ? 'mua' : 'bán' }} thẻ cào
                </h3>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-striped table-vcenter text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Loại thẻ</th>
                                <th>Trạng thái</th>
                                <th>Auto</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($stt = 1)
                            @foreach($settings as $setting)
                                <tr>
                                    <th>{{ $stt++ }}</th>
                                    <td>{{ ucfirst($setting->name) }}</td>
                                    <td>{!! $setting->getStatusHtml() !!}</td>
                                    <td>{!! $setting->getAutoHtml() !!}</td>
                                    <td style="min-width: 300px">
                                        {!! $setting->getBtnStatusHtml($type) !!}
                                        {!! $setting->getBtnAutoHtml($type) !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
{{--    <script>--}}
{{--        window.ChangeRateCard = {--}}
{{--            submit : function(el){--}}
{{--                let _form = new FormData(el);--}}
{{--                let _url = el.getAttribute('action');--}}
{{--                Request.ajax(_url, _form, function(result){--}}
{{--                    if(result.success) {--}}
{{--                        alertify.alert('Notification', result.message)--}}
{{--                        $('.alertify .ajs-header').addClass('alert-primary');--}}
{{--                        return false;--}}
{{--                    }--}}

{{--                    let elError = $('<ul />').addClass('list-style-decimal');--}}
{{--                    for(let i = 0; i < result.errors.length; i++){--}}
{{--                        let err = $('<li />').text(result.errors[i]);--}}
{{--                        elError.append(err);--}}
{{--                    }--}}

{{--                    alertify.alert('Error', elError.prop('outerHTML'))--}}
{{--                    $('.alertify .ajs-header').addClass('alert-danger');--}}
{{--                    return false;--}}
{{--                });--}}
{{--                return false;--}}
{{--            },--}}
{{--            calcDeviant : function (el){--}}
{{--                let rate = el.getAttribute('data-rate');--}}
{{--                let val = el.value.trim();--}}
{{--                if(parseFloat(val) != val){--}}
{{--                    return;--}}
{{--                }--}}
{{--                let deviant = parseFloat(val) - parseFloat(rate);--}}
{{--                $(el).closest('tr').find('.deviant').text(deviant);--}}
{{--            }--}}
{{--        }--}}
{{--    </script>--}}
@endsection
