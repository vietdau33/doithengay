@extends('layout')
@section('contents')
    <div class="container-fluid">
        <div class="header-content d-flex justify-content-center flex-column">
            <h3 class="mt-5 font-weight-bold text-center">Nạp tiền</h3>
        </div>
        <div class="body-content">
            <div class="row align-items-center mt-3">
                <div class="col-12">
                    @if($banks->count() > 0)
                        <p class="text-center font-weight-bold">Vui lòng chuyển khoản sang 1 trong số các tài khoản sau:</p>
                        <table class="table table-striped table-bordered">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">Ngân hàng</th>
                                <th scope="col">Thông tin tài khoản</th>
                                <th scope="col">Nội dung</th>
                                <th scope="col">Nạp tối thiểu</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($banks as $bank)
                                <tr>
                                    <td class="font-weight-bold">{{ $bank->bank_type }}</td>
                                    <td>{!! nl2br(htmlspecialchars($bank->bank_info)) !!}</td>
                                    <td>{{ buildBankContent($bank->bank_content) }}</td>
                                    <td>{{ $bank->bank_min }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
