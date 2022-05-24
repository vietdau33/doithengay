<div class="table-responsive">
    <table class="table table-hover table-responsive-md text-center custom-scrollbar">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Thời gian</th>
            <th scope="col">Username gửi / nhận</th>
            <th scope="col">Số tiền</th>
            <th scope="col">Nội dung</th>
        </tr>
        </thead>
        <tbody>
        @php($stt = 1)
        @foreach($logs as $log)
            <tr>
                <th scope="row">{{ $stt++ }}</th>
                <td style="min-width: 130px;">{{ date('H:i d/m/Y', strtotime($log->created_at)) }}</td>
                <td style="min-width: 200px;">
                    @if($log->user_id == user()->id)
                        Chuyển đến: {{ $log->receive->username ?? '' }}
                    @else
                        Nhận từ: {{ $log->user->username ?? '' }}
                    @endif
                </td>
                <td style="min-width: 180px;">{{ number_format($log->money) }} VNĐ</td>
                <td>{{ $log->content }}</td>
            </tr>
        @endforeach
        @if($logs->count() == 0)
            <tr>
                <td colspan="6" style="min-width: 615px;">Không có lịch sử</td>
            </tr>
        @endif
        </tbody>
    </table>
    {!! $logs->links('admin.user.logs.paginate', ['type' => 'transfer']) !!}
</div>
