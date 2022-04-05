@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Danh sách thẻ</p>
        </div>
        <div class="body-content">
            <div class="box-content box-pay-card p-3 mt-4">
                <div class="add-new text-right mb-3">
                    <a href="{{ route('bank.add') }}" class="btn btn-primary">Thêm mới</a>
                </div>
                <table class="table table-hover table-responsive text-center custom-scrollbar">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Loại thẻ</th>
                            <th scope="col">Tên thẻ</th>
                            <th scope="col">Số thẻ</th>
                            <th scope="col">Tên chủ thẻ</th>
                            <th scope="col">Ngày thêm</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($stt = 1)
                        @foreach($banks as $bank)
                            <tr>
                                <th scope="row">{{ $stt++ }}</th>
                                <td style="min-width: 150px;">{{ getTypeBank($bank['type']) }}</td>
                                <td style="min-width: 150px;">{{ getNameBank($bank['type'], $bank['name']) }}</td>
                                <td>{{ $bank['account_number'] }}</td>
                                <td style="min-width: 150px">{{ $bank['account_name'] }}</td>
                                <td style="min-width: 130px;">{{ date('d/m/Y', strtotime($bank['created_at'])) }}</td>
                            </tr>
                        @endforeach
                        @if($banks->count() == 0)
                            <tr>
                                <td colspan="6" style="min-width: 615px;">Chưa có thẻ ngân hàng nào</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
