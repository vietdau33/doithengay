<div class="table-responsive">
    <table class="table table-striped table-bordered table-responsive-md">
        <thead class="table-light">
        <tr>
            <th scope="row">#</th>
            <th scope="row">Thời gian</th>
            <th scope="row">Loại thẻ</th>
            <th scope="row">Mệnh giá</th>
            <th scope="col">Số lượng</th>
            <th scope="row">Trạng thái</th>
            <th scope="row">Mua</th>
            <th scope="row">Message</th>
            <th scope="row">Chiết khẩu (%)</th>
            <th scope="row">Tổng tiền</th>
        </tr>
        </thead>
        <tbody>
        @php($stt = 1)
        @foreach($logs as $log)
            <tr>
                <td>{{ $stt++ }}</td>
                <td style="min-width: 120px;">{{ date('H:i d-m-Y', strtotime($log->created_at)) }}</td>
                <td>{{ ucfirst($log->card_buy) }}</td>
                <td>{{ number_format($log->money_buy) }}</td>
                <td>{{ $log->quantity }}</td>
                <td>{!! $log->getStatus() !!}</td>
                <td>{{ $log->type_buy == 'fast' ? 'Mua nhanh' : 'Mua chậm' }}</td>
                <td>{{ $log->message }}</td>
                <td>{{ $log->rate_buy }}%</td>
                <td>{{ number_format($log->money_after_rate) }}đ</td>
            </tr>
        @endforeach
        @if($logs->count() <= 0)
            <tr>
                <td colspan="9" class="text-center">Không có dữ liệu</td>
            </tr>
        @endif
        </tbody>
    </table>
    {!! $logs->links('admin.user.logs.paginate', ['type' => 'buy_card']) !!}
</div>
