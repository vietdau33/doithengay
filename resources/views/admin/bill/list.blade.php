@extends('admin_layout')
@section('contents')
    <div class="content">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    {{ config("bill.$type.text") }}
                </h3>
            </div>
            <div class="block-content">
                @include('admin.filter')
                <div class="table-responsive">
                    <table class="table table-striped table-vcenter text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Loại tài khoản</th>
                                <th>Số tiền</th>
                                <th>Thanh toán</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($stt = 1)
                            @foreach($bills as $bill)
                                <tr>
                                    <td>{{ $stt++ }}</td>
                                    <td class="font-w600">{{ $bill->user->username ?? '' }}</td>
                                    <td>{{ config("bill.$type.vendor.{$bill->vendor_id}.name") }}</td>
                                    <td>{{ number_format($bill->money) }}</td>
                                    <td>{{ config("payment.method.{$bill->type_pay}.name") }}</td>
                                    <td>{!! $bill->getStatusHtml(true) !!}</td>
                                    <td>{{ date('H:i d/m/Y', strtotime($bill->created_at)) }}</td>
                                    <td style="min-width: 200px">
                                        @if($bill->status === 0)
                                            <a onclick="return confirm('Chắn chắn xác nhận hóa đơn này?')"
                                               href="{{ route('admin.bill.change-status', ['id' => $bill->id, 'status' => 1]) }}"
                                               class="btn btn-primary">Xác nhận</a>
                                            <a onclick="return confirm('Chắn chắn muốn hủy hóa đơn này?')"
                                               href="{{ route('admin.bill.change-status', ['id' => $bill->id, 'status' => 3]) }}"
                                               class="btn btn-danger">Từ chối</a>
                                        @elseif($bill->status === 1)
                                            <a onclick="return confirm('Chắn chắn đã thanh toán?')"
                                               href="{{ route('admin.bill.change-status', ['id' => $bill->id, 'status' => 2]) }}"
                                               class="btn btn-success">Đã thanh toán</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @if($bills->count() <= 0)
                                <tr>
                                    <td colspan="8">Không có hóa đơn cần thanh toán</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
