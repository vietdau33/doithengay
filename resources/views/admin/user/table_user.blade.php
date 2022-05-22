<table class="table table-striped table-vcenter text-center">
    <thead>
    <tr>
        <th>#</th>
        <th>Tài khoản</th>
        <th>Họ tên</th>
        <th>Email</th>
        <th>Số điện thoại</th>
        <th>Số tiền</th>
        <th>Ngày tạo</th>
        @if($isActivePage)
            <th>Level</th>
        @endif
        <th>Hành động</th>
    </tr>
    </thead>
    <tbody>
    @php($stt = 1)
    @foreach($users as $user)
        <tr>
            <td>{{ $stt++ }}</td>
            <td class="font-w600">{{ $user->username }}</td>
            <td style="min-width: 150px">{{ $user->fullname }}</td>
            <td style="min-width: 170px">{{ $user->email }}</td>
            <td style="min-width: 110px">{{ $user->phone }}</td>
            <td class="row-money" style="min-width: 100px">{{ number_format($user->money) }}</td>
            <td style="min-width: 100px">{{ date('d/m/Y', strtotime($user->created_at)) }}</td>
            @if($isActivePage)
                <td style="min-width: 150px">
                    <select class="form-control select-change-user-type" data-username="{{ $user->username }}">
                        <option {{ $user->type_user == 'nomal' ? 'selected' : '' }} value="nomal">Thành viên</option>
                        <option {{ $user->type_user == 'daily' ? 'selected' : '' }} value="daily">Đại lý</option>
                        <option {{ $user->type_user == 'tongdaily' ? 'selected' : '' }} value="tongdaily">Tổng đại lý</option>
                    </select>
                </td>
            @endif
            <td style="min-width: {{ $isActivePage ? '230px' : '130px' }}">
                @if($isActivePage)
                    <a href="#" data-username="{{ $user->username }}" class="btn btn-primary btn-plus-money p-1">Cộng tiền</a>
                    <a href="{{ route('admin.user.change-active', ['id' => $user->id, 'status' => (int)!$user->inactive]) }}" class="btn btn-danger p-1" onclick="return confirm('Chắc chắn chặn người này?')">Chặn</a>
                    <button class="btn btn-success p-1 btn-view-logs" data-id="{{ $user->id }}" data-username="{{ $user->username }}">Xem logs</button>
                @else
                    <a href="{{ route('admin.user.change-active', ['id' => $user->id, 'status' => (int)!$user->inactive]) }}" class="btn btn-primary" onclick="return confirm('Chắc chắn mở chặn người này?')">Mở</a>
                @endif
            </td>
        </tr>
    @endforeach
    @if($users->count() <= 0)
        <tr>
            <td colspan="{{ $isActivePage ? '9' : '8' }}">Không có người dùng nào {{ $isActivePage ? 'đang hoạt động!' : 'bị chặn!' }}</td>
        </tr>
    @endif
    </tbody>
</table>
