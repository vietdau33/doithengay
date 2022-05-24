<div class="table-responsive">
    <table class="table table-striped table-vcenter text-center">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Ngày giờ</th>
            <th scope="col">IP</th>
            <th scope="col">Hành động</th>
        </tr>
        </thead>
        <tbody>
        @php($stt = 1)
        @foreach($logs as $log)
            <tr>
                <td>{{ $stt++ }}</td>
                <td style="min-width: 200px;">{{ date('H:i d/m/Y', strtotime($log->created_at)) }}</td>
                <td style="min-width: 140px">{{ $log->ip }}</td>
                <td>{{ $log->message }}</td>
            </tr>
        @endforeach
        @if(count($logs) <= 0)
            <tr>
                <td colspan="4">Không có bản ghi log nào!</td>
            </tr>
        @endif
        </tbody>
    </table>
    {!! $logs->links('admin.user.logs.paginate', ['type' => 'activity']) !!}
</div>
