<div class="table-responsive">
    <table class="table table-striped table-bordered table-vcenter text-center">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Thời gian</th>
            <th scope="col">Ngân hàng</th>
            <th scope="col">Số tài khoản</th>
            <th scope="col">Số tiền</th>
            <th scope="col">Nội dung</th>
            <th scope="col">Trạng thái</th>
        </tr>
        </thead>
        <tbody>
        @php($stt = 1)
        @foreach($logs as $log)
            @php($success = $log->status == 1)
            <tr>
                <td>{{ $stt++ }}</td>
                <td style="max-width: 100px">{{ date('H:i d/m/Y', strtotime($log->created_at)) }}</td>
                <td>{{ $log->bank }}</td>
                <td>{{ $log->number }}</td>
                <td>{{ number_format($log->recharge) }}</td>
                <td>{{ $log->messenger }}</td>
                <td><span class="text-{{ $success ? 'success' : 'danger' }}">{{ $success ? 'Thành công': 'Thất bại' }}</span></td>
            </tr>
        @endforeach
        @if(count($logs) <= 0)
            <tr>
                <td colspan="7">Không có lịch sử nào!</td>
            </tr>
        @endif
        </tbody>
    </table>
    {!! $logs->links('admin.user.logs.paginate', ['type' => 'recharge']) !!}
</div>
