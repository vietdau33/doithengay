@extends('admin_layout')
@section('contents')
    <div class="content">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Ghi logs hệ thống</h3>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-striped table-vcenter text-center">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Người thay đổi</th>
                            <th scope="col">Hành động</th>
                            <th scope="col">Nội dung</th>
                            <th scope="col">Ngày giờ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($stt = 1)
                        @foreach($logs as $log)
                            <tr>
                                <td>{{ $stt++ }}</td>
                                <td style="min-width: 150px;">{{ $log->user->username ?? 'Không có' }}</td>
                                <td style="min-width: 150px;">{{ $log->contents['mgs'] ?? $log->contents['content'] ?? '' }}</td>
                                <td class="text-left" data-contents="{{ json_encode($log->contents) }}"></td>
                                <td style="max-width: 100px">{{ date('H:i d/m/Y', strtotime($log->created_at)) }}</td>
                            </tr>
                        @endforeach
                        @if(count($logs) <= 0)
                            <tr>
                                <td colspan="4">Không có bản ghi log nào!</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    {!! $logs->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('[data-contents]').each(function () {
            try {
                let html = $('<ul />');
                let contents = $(this).attr('data-contents');
                contents = JSON.parse(contents);
                for (let key in contents) {
                    if (key == 'mgs' || key == 'content') {
                        continue;
                    }
                    let data = contents[key];
                    key = key.split('_').join(' ');
                    html.append(
                        $('<li />').text(key + ': ' + data)
                    );
                }
                $(this).removeAttr('data-contents').empty().append(html);
            } catch (e) {
                console.log(e)
            }
        })
    </script>
@endsection
