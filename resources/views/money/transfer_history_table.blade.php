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
    @foreach($histories as $history)
        <tr>
            <th scope="row">{{ $stt++ }}</th>
            <td style="min-width: 130px;">{{ date('H:i d/m/Y', strtotime($history->created_at)) }}</td>
            <td style="min-width: 200px;">
                @if($history->user_id == user()->id)
                    Chuyển đến: {{ $history->receive->username ?? '' }}
                @else
                    Nhận từ: {{ $history->user->username ?? '' }}
                @endif
            </td>
            <td style="min-width: 180px;">{{ number_format($history->money) }} VNĐ</td>
            <td>{{ $history->content }}</td>
        </tr>
    @endforeach
    @if($histories->count() == 0)
        <tr>
            <td colspan="6" style="min-width: 615px;">Không có lịch sử</td>
        </tr>
    @endif
    </tbody>
</table>
