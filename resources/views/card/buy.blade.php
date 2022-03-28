@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Mua thẻ cào</p>
        </div>
        <div class="body-content">
            <div class="box-content box-pay-card p-3 mt-4">
                <form action="#" method="POST">
                    @csrf
                    <div class="form-header">
                        <img src="{{ asset('image/pay.png') }}" alt="Pay">
                        <span>Chọn loại thẻ</span>
                    </div>
                    <div class="form-group d-flex flex-wrap justify-content-center ">
                        @foreach(config('card.list') as $key => $card)
                            <label class="box-card" for="card-{{ $key }}">
                                <img src="{{ asset($card['image']) }}" alt="{{ $key }}">
                                <input type="radio" id="card-{{ $key }}" name="card-buy">
                                <span class="checkbox-custom"></span>
                            </label>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
