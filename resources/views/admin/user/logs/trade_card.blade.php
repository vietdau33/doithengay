<div class="table-responsive">
    <table class="table table-striped table-bordered table-responsive-md">
        <thead class="table-light">
        <tr>
            <th scope="row">#</th>
            <th scope="row">Thời gian</th>
            <th scope="row">Loại thẻ</th>
            <th scope="row">Mệnh giá</th>
            <th scope="row">Trạng thái</th>
            <th scope="row">Mua</th>
            <th scope="row">Chiết khẩu (%)</th>
            <th scope="row">Tiền nhận</th>
            <th scope="row">Số serial</th>
            <th scope="row">Mã thẻ</th>
        </tr>
        </thead>
        <tbody>
        @php($stt = 1)
        @foreach($logs as $log)
            <tr>
                <td>{{ $stt++ }}</td>
                <td style="min-width: 120px;">{{ date('d-m-Y', strtotime($log->created_at)) }}</td>
                <td>{{ $log->getNameTelco() }}</td>
                <td>{{ number_format($log->card_money) }}</td>
                <td>{!! $log->getStatusHtml() !!}</td>
                <td>{{ $log->type_trade }}</td>
                <td>{{ $log->rate_use ?? 0 }}%</td>
                <td>{{ number_format($log->money_real) }}đ</td>
                <td>{{ $log->card_serial }}</td>
                <td>{{ $log->card_number }}</td>
            </tr>
        @endforeach
        @if($logs->count() <= 0)
            <tr>
                <td colspan="9" class="text-center">Không có dữ liệu</td>
            </tr>
        @endif
        </tbody>
    </table>
    {!! $logs->links('admin.user.logs.paginate', ['type' => 'trade_card']) !!}
</div>
