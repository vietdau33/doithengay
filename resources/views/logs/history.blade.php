@extends('layout')
@section('contents')
    <div class="container-fluid">
        <div class="block">
            <div class="block-header mt-3 text-center mb-3">
                <h3 class="block-title">Lịch sử truy cập</h3>
            </div>
            <div class="alert alert-warning">
                Nếu có thấy lịch sử bất thường, hãy liên hệ với ADMIN để kiểm tra chi tiết!
            </div>
            <div class="block-content">
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
