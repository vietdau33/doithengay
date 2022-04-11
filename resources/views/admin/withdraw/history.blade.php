@extends('admin_layout')
@section('contents')
    <div class="content">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Lịch sử yêu cầu rút tiền</h3>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-striped table-vcenter text-center">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Tài khoản</th>
                                <th scope="col">Số tiền</th>
                                <th scope="col">Ghi chú</th>
                                <th scope="col">Ngày thanh toán</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($stt = 1)
                            @foreach($lists as $list)
                                <tr>
                                    <td>{{ $stt++ }}</td>
                                    <td style="min-width: 150px;">{!! $list->getStatus() !!}</td>
                                    <td class="font-w600">{{ $list->user->username ?? '' }}</td>
                                    <td>{{ number_format($list->money) }}</td>
                                    <td style="max-width: 250px">{{ $list->note }}</td>
                                    <td style="max-width: 100px">{{ date('d/m/Y', strtotime($list->updated_at)) }}</td>
                                </tr>
                            @endforeach
                            @if($lists->count() <= 0)
                                <tr>
                                    <td colspan="5">Không có yêu cầu nào</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
