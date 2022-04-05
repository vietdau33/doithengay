@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Danh sách thẻ đã mua</p>
        </div>
        <div class="body-content">
            <div class="box-content box-cards p-3 mt-4">
                @foreach($lists as $list)
                    <div class="box-card-info d-flex flex-column align-items-center">
                        <h4 class="font-weight-bold">Thẻ {{ $list['Telco'] }}: {{ number_format($list['Amount']) }}</h4>
                        <ul style="padding-left: 20px">
                            <li>Serial: <b>{{ $list['Serial'] }}</b></li>
                            <li>Mã thẻ: <b>{{ $list['Pin'] }}</b></li>
                        </ul>
                    </div>
                    <hr />
                @endforeach
            </div>
        </div>
    </div>
@endsection
