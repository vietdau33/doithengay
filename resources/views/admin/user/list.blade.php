@extends('admin_layout')
@section('contents')
    <div class="content">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    {{ $action == 'active' ? 'Danh sách thành viên hoạt động!' : 'Danh sách thành viên đã bị chặn!' }}
                </h3>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-striped table-vcenter text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tài khoản</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($stt = 1)
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $stt++ }}</td>
                                    <td class="font-w600">{{ $user->username ?? '' }}</td>
                                    <td>{{ $user->fullname }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ date('d/m/Y', strtotime($user->created_at)) }}</td>
                                    <td>
                                        @if($action == 'active')
                                            <a href="{{ route('admin.user.change-active', ['id' => $user->id, 'status' => (int)!$user->inactive]) }}" class="btn btn-danger" onclick="return confirm('Chắc chắn chặn người này?')">Chặn</a>
                                        @else
                                            <a href="{{ route('admin.user.change-active', ['id' => $user->id, 'status' => (int)!$user->inactive]) }}" class="btn btn-primary" onclick="return confirm('Chắc chắn mở chặn người này?')">Mở</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @if($users->count() <= 0)
                                <tr>
                                    <td colspan="7">Không có người dùng nào {{ $action == 'active' ? 'đang hoạt động!' : 'bị chặn!' }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
