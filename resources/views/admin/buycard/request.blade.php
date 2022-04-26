@extends('admin_layout')
@section('contents')
    <div class="content">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Danh sách yêu cầu mua thẻ</h3>
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
                            <th>Số lượng</th>
                            <th>Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($stt = 1)
                        @foreach($requests ?? [] as $request)
                            <tr>
                                <td>{{ $stt++ }}</td>
                                <td class="font-w600">{{ $request->user->username ?? '' }}</td>
                                <td>{{ ucfirst($request->card_buy) }}</td>
                                <td style="max-width: 250px">{{ number_format($request->money_buy) }}</td>
                                <td style="max-width: 250px">{{ time_elapsed_string($request->created_at) }}</td>
                                <td style="max-width: 100px">{{ $request->quantity }}</td>
                                <td style="width: 120px">
                                    @if($request->status === 0)
                                        <a onclick="return confirm('Chắn chắn muốn thay đổi status?')"
                                           href="{{ route('admin.buycard-request.status', ['id' => $request->id, 'status' => 1]) }}"
                                           class="btn btn-primary">Xác nhận</a>
                                        <a onclick="return confirm('Chắn chắn muốn thay đổi status?')"
                                           href="{{ route('admin.buycard-request.status', ['id' => $request->id, 'status' => 3]) }}"
                                           class="btn btn-danger mt-1">Từ chối</a>
                                    @elseif($request->status === 2)
                                        <span class="text-success">Thành công</span>
                                    @else
                                        <span class="text-primary">Đã xác nhận</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($requests->count() <= 0)
                            <tr>
                                <td colspan="7">Không có yêu cầu nào</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
