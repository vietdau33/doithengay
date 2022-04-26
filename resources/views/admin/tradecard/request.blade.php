@extends('admin_layout')
@section('contents')
    <div class="content">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Danh sách yêu cầu đổi thẻ</h3>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-striped table-vcenter text-center">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Tài khoản</th>
                            <th>Loại thẻ</th>
                            <th>Mệnh giá</th>
                            <th>Thời gian</th>
                            <th>Số serial</th>
                            <th>Mã thẻ</th>
                            <th>Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($stt = 1)
                        @foreach($requests ?? [] as $request)
                            <tr>
                                <td>{{ $stt++ }}</td>
                                <td class="font-w600">{{ $request->user->username ?? '' }}</td>
                                <td>{{ $request->getNameTelco() }}</td>
                                <td style="max-width: 250px">{{ number_format($request->card_money) }}</td>
                                <td style="max-width: 250px">{{ time_elapsed_string($request->created_at) }}</td>
                                <td style="max-width: 100px">{{ $request->card_serial }}</td>
                                <td style="max-width: 100px">{{ $request->card_number }}</td>
                                <td style="width: 120px">
                                    @if($request->status === 1)
                                        <a onclick="return confirm('Chắn chắn muốn thay đổi status?')"
                                           href="{{ route('admin.tradecard-request.status', ['id' => $request->id, 'status' => 2]) }}"
                                           class="btn btn-primary">Xác nhận</a>
                                        <a onclick="return confirm('Chắn chắn muốn thay đổi status?')"
                                           href="{{ route('admin.tradecard-request.status', ['id' => $request->id, 'status' => 4]) }}"
                                           class="btn btn-danger mt-1">Từ chối</a>
                                    @elseif($request->status === 3)
                                        <span class="text-success">Thành công</span>
                                    @elseif($request->status === 4)
                                        <span class="text-danger">Đã từ chối</span>
                                    @else
                                        <span class="text-primary">Đã xác nhận</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($requests->count() <= 0)
                            <tr>
                                <td colspan="8">Không có yêu cầu nào</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
