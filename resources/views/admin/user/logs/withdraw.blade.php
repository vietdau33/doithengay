<div class="table-responsive">
    <table class="table table-hover table-responsive text-center custom-scrollbar">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Trạng thái</th>
            <th scope="col">Ngày tạo</th>
            <th scope="col">Ngân hàng</th>
            <th scope="col">Số tiền</th>
            <th scope="col">Ghi chú</th>
        </tr>
        </thead>
        <tbody>
        @php($stt = 1)
        @foreach($logs as $log)
            @php($bank = $log->bank_relation)
            <tr>
                <th scope="row">{{ $stt++ }}</th>
                <td style="min-width: 150px;">{!! $log->getStatus() !!}</td>
                <td style="min-width: 130px;">{{ date('d/m/Y', strtotime($log->created_at)) }}</td>
                <td style="min-width: 250px">{{ $bank->account_number }} ({{ getNameBank($bank->type, $bank->name) }} - {{ $bank->account_name }})</td>
                <td style="min-width: 180px;">{{ number_format($log->money) }} VNĐ</td>
                <td style="min-width: 200px;">{{ $log->note }}</td>
            </tr>
        @endforeach
        @if($logs->count() == 0)
            <tr>
                <td colspan="6" style="min-width: 615px;">Không có lịch sử</td>
            </tr>
        @endif
        </tbody>
    </table>
    {!! $logs->links('admin.user.logs.paginate', ['type' => 'withdraw']) !!}
</div>
