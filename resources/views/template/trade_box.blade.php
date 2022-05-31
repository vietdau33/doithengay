@php
    $rates = \App\Models\RateCard::getListCardTrade();
    $listNotAuto = \App\Models\CardListModel::whereAuto('0')->whereType('trade')->get()->toArray();
    $listNotAuto = array_column($listNotAuto, 'name');
    $listCard = getListCardTrade($rates);
    $histories = \App\Models\TradeCard::getTodayHistory();
    $totals = \App\Models\TradeCard::getTotals();
@endphp

<div class="container mt-4 mb-4">
    <h3 class="text-center font-weight-bold">ĐỔI THẺ</h3>
    <ul class="list-notif mb-4">
        <li>
            <img src="{{ asset('image/arrow-1.gif') }}" alt="Arrow">
            <span class="font-weight-bold">Chọn chính xác loại thẻ và mệnh giá thẻ cào.</span>
        </li>
        <li>
            <img src="{{ asset('image/arrow-1.gif') }}" alt="Arrow">
            <span class="font-weight-bold">Nếu chọn sai loại thẻ, thẻ cào sẽ bị vô hiệu hóa.</span>
        </li>
        <li>
            <img src="{{ asset('image/arrow-1.gif') }}" alt="Arrow">
            <span class="font-weight-bold">Nếu chọn sai mệnh giá, hệ thống sẽ trừ 50% số tiền nhận được..</span>
        </li>
        <li>
            <img src="{{ asset('image/arrow-1.gif') }}" alt="Arrow">
            <span class="font-weight-bold">Không nhận API game bài, cổng game bài cố tình đấu api sẽ bị khóa.</span>
        </li>
        <li>
            <img src="{{ asset('image/arrow-1.gif') }}" alt="Arrow">
            <span class="font-weight-bold">Hướng dẫn tích hợp API gạch thẻ tự động <a href="{{ route('connect-api') }}">tại đây</a>.</span>
        </li>
    </ul>
</div>
<div class="container mb-3 container-trade-card-home">
    <div class="form-group form-trade-card-home position-relative d-none" data-id="template">
        <select name="card_type" class="form-control mb-1 mr-1 select_card_type">
            <option value="">Loại thẻ</option>
            @foreach($listCard as $key => $card)
                <option value="{{ $card['name'] }}">{{ ucfirst($card['name']) }}</option>
            @endforeach
        </select>
        <select name="card_money" class="form-control mb-1 mr-1 select_card_money">
            <option value="">Mệnh giá</option>
        </select>
        <select name="type_trade" class="form-control mb-1 mr-1 select_type_trade">
            @if(!logined() || user()->type_user == 'nomal')
                <option value="">Phương thức gạch thẻ</option>
                <option value="slow" data-type="slow">Gạch chậm: 0% - 0VNĐ</option>
                <option value="fast" data-type="fast">Gạch nhanh: 0% - 0VNĐ</option>
            @else
                <option value="slow" data-type="slow">Chiết khấu: 0% - 0VNĐ</option>
            @endif
        </select>
        <input type="text" name="card_serial" class="form-control mb-1 mr-1" placeholder="Số serial">
        <input type="text" name="card_number" class="form-control mb-1 mr-1" placeholder="Mã thẻ">
        <button class="btn btn-danger btn-add-area-trade" onclick="RemoveRowTrade(this)">
            <img class="m-0" src="{{ asset('image/icon/trash.svg') }}" alt="plus">
        </button>
        <div class="alert alert-info alert-status w-100 mb-1 p-1 d-none mr-1"><img src="{{ asset('image/loading.svg') }}" style="width: 24px" alt="Loading"> Đang thực hiện gạch thẻ...</div>
    </div>
    <div class="form-group d-flex form-trade-card-home position-relative">
        <select name="card_type" class="form-control mb-1 mr-1 select_card_type">
            <option value="">Loại thẻ</option>
            @foreach($listCard as $key => $card)
                <option value="{{ $card['name'] }}">{{ ucfirst($card['name']) }}</option>
            @endforeach
        </select>
        <select name="card_money" class="form-control mb-1 mr-1 select_card_money">
            <option value="">Mệnh giá</option>
        </select>
        <select name="type_trade" class="form-control mb-1 mr-1 select_type_trade">
            @if(!logined() || user()->type_user == 'nomal')
                <option value="">Phương thức gạch thẻ</option>
                <option value="slow" data-type="slow">Gạch chậm: 0% - 0VNĐ</option>
                <option value="fast" data-type="fast">Gạch nhanh: 0% - 0VNĐ</option>
            @else
                <option value="slow" data-type="slow">Chiết khấu: 0% - 0VNĐ</option>
            @endif
        </select>
        <input type="text" name="card_serial" class="form-control mb-1 mr-1" placeholder="Số serial">
        <input type="text" name="card_number" class="form-control mb-1 mr-1" placeholder="Mã thẻ">
        <button class="btn btn-success btn-add-area-trade" onclick="CopyRowTrade()">
            <img src="{{ asset('image/icon/plus.svg') }}" alt="plus">
            <span>Thêm</span>
        </button>
        <div class="alert alert-info alert-status w-100 mb-1 p-1 d-none mr-1"><img src="{{ asset('image/loading.svg') }}" style="width: 24px" alt="Loading"> Đang thực hiện gạch thẻ...</div>
    </div>
    <div id="row_save_position_add_trade">
        @if(logined() && user()->type_user == 'nomal')
            <div class="alert alert-warning">
                <ul class="mb-0" style="list-style: decimal; padding-left: 20px">
                    <li>Đối với gạch chậm, thời gian xác minh thẻ tối đa là 2 phút.</li>
                    <li>Sau 2 phút kể từ khi gửi thẻ, nếu hệ thống không xác minh được thẻ thì thẻ sẽ bị đẩy sang gạch nhanh.</li>
                </ul>
            </div>
        @endif
    </div>
    <div class="btn-trade-card text-center">
        <button class="btn btn-warning btn-trade-card-all">Gạch thẻ</button>
    </div>
</div>
@if(logined())
    <div class="container-fluid">
        <h3 class="mt-3 font-weight-bold">Lịch sử đổi thẻ</h3>
        <div class="row-filter d-flex">
            <select name="filter_card_type" class="form-control mr-2">
                <option value="">Loại thẻ</option>
                @foreach($rates as $card)
                    <option data-type="{{ end($card)['name'] }}" value="{{ end($card)['rate_id'] ?? '0' }}">{{ ucfirst(end($card)['name']) }}</option>
                @endforeach
            </select>
            <select name="filter_money" class="form-control mr-2">
                <option value="">Mệnh giá</option>
            </select>
            <select name="filter_status" class="form-control mr-2">
                <option value="">Trạng thái</option>
                <option value="0">Đang kiểm tra</option>
                <option value="1">Thẻ đúng</option>
                <option value="2">Thẻ sai</option>
                <option value="3">Thẻ sai mệnh giá</option>
            </select>
            <input type="text" name="filter_from_date" class="form-control mr-2" value="{{ date('Y-m-d') }}" data-date-picker>
            <input type="text" name="filter_to_date" class="form-control mr-2" value="{{ date('Y-m-d') }}" data-date-picker>
            <button class="btn btn-success btn-filter" style="min-width: 150px;">Lọc dữ liệu</button>
        </div>
        <div class="table-filter mt-2">
            @include('card.trade_history_table')
        </div>
    </div>
    <div class="container-fluid">
        <h3 class="mt-3 font-weight-bold">Thống kê đổi thẻ</h3>
        <div class="row-filter d-flex">
            <select name="filter_card_type_total" class="form-control mr-2">
                <option value="">Loại thẻ</option>
                @foreach($rates as $card)
                    <option data-type="{{ end($card)['name'] }}" value="{{ end($card)['rate_id'] ?? '0' }}">{{ ucfirst(end($card)['name']) }}</option>
                @endforeach
            </select>
            <input type="text" name="filter_from_date_total" class="form-control mr-2" value="{{ date('Y-m-d') }}" data-date-picker>
            <input type="text" name="filter_to_date_total" class="form-control mr-2" value="{{ date('Y-m-d') }}" data-date-picker>
            <button class="btn btn-success btn-filter-total" style="min-width: 150px;">Lọc dữ liệu</button>
        </div>
        <div class="table-filter-total mt-2">
            @include('card.trade_total_table')
        </div>
    </div>
@endif
<script>
    const rates = {!! json_encode($rates) !!};
    const listNotAuto = {!! json_encode($listNotAuto) !!};

    const resetTradeCard = function (parentBox = null) {
        if (parentBox == null) {
            parentBox = $('.container-trade-card-home');
        }
        parentBox.find('[name="type_trade"]').each(function () {
            @if(!logined() || user()->type_user == 'nomal')
            $(this).find('[data-type="slow"]').text('Gạch chậm: 0% - 0VNĐ');
            $(this).find('[data-type="fast"]').text('Gạch nhanh: 0% - 0VNĐ');
            @else
            $(this).find('[data-type="slow"]').text('Chiết khấu: 0% - 0VNĐ');
            @endif
        });
    }

    $('.container-trade-card-home').on('change', '[name="card_type"]', function () {
        let val = $(this).val();
        let rate = rates[val];
        let parentBox = $(this).parent();
        let areaMoney = parentBox.find('.select_card_money');

        try {
            resetTradeCard(parentBox);
            if (listNotAuto.indexOf(val) != -1) {
                parentBox.find('[name="type_trade"] [data-type="fast"]').addClass('d-none');
                parentBox.find('[name="type_trade"] [data-type="slow"]').prop('selected', true);
            } else {
                parentBox.find('[name="type_trade"] [data-type="fast"]').removeClass('d-none');
            }
        } catch (e) {
            console.log(e)
        }

        areaMoney.empty();
        areaMoney.append('<option value="">Mệnh giá</option>');

        $.each(rate, function (index, r) {
            let money = r.price;
            let tempEl = $("<option />");
            let id = 'money-' + money;
            let rateUse = 0;
            let rateSlow = 0;
            @if(logined())
                @if(user()->type_user == 'nomal')
                    rateUse = r.rate_use;
                    rateSlow = r.rate_slow;
                @elseif(user()->type_user == 'daily')
                    rateUse = r.rate_daily;
                    rateSlow = r.rate_daily;
                @elseif(user()->type_user == 'tongdaily')
                    rateUse = r.rate_tongdaily;
                    rateSlow = r.rate_tongdaily;
                @endif
            @else
                rateUse = r.rate_use;
                rateSlow = r.rate_slow;
            @endif

            tempEl.text(App.setPriceFormat(money));
            tempEl.attr('data-id', id)
                .attr('data-value', money)
                .attr('data-rate', rateUse)
                .attr('data-rate-slow', rateSlow);
            areaMoney.append(tempEl);
        });
    });

    $('.container-trade-card-home').on('change', '[name="card_money"]', function () {
        const parentBox = $(this).parent();
        const optionEl = $(this).find('option:selected');
        const rate = parseFloat(optionEl.attr('data-rate'));
        const rateSlow = parseFloat(optionEl.attr('data-rate-slow'));
        const money = parseInt(optionEl.attr('data-value'));
        const moneySlow = App.setPriceFormat(money - (money * rateSlow / 100));
        const moneyFast = App.setPriceFormat(money - (money * rate / 100));

        resetTradeCard(parentBox);
        @if(!logined() || user()->type_user == 'nomal')
        parentBox.find('[data-type="slow"]').text('Gạch chậm: ' + rateSlow + '% - ' + moneySlow + 'VNĐ');
        parentBox.find('[data-type="fast"]').text('Gạch nhanh: ' + rate + '% - ' + moneyFast + 'VNĐ');
        @else
        parentBox.find('[data-type="slow"]').text('Chiết khấu: ' + rateSlow + '% - ' + moneySlow + 'VNĐ');
        @endif
    });

    $('.btn-trade-card-all').on('click', async function () {
        @if(!logined())
        window.location.href = '{{ route('trade-card') }}';
        @else
        const cardArea = $('.container-trade-card-home');
        let hasError = false;
        cardArea.find('.form-trade-card-home[data-id="template"]').remove();
        //validate select data
        cardArea.find('select, input').each(function(){
            if(this.value.trim() == '') {
                hasError = true;
                return false;
            }
        });
        if(hasError) {
            alertify.alert('Error', "Có một vài thông tin chưa được chọn. Hãy kiểm tra và thử lại!");
            $('.alertify .ajs-header').addClass('alert-danger').removeClass('alert-success');
            return false;
        }
        // $(this).remove();
        // cardArea.find('select').prop('disabled', true);
        // cardArea.find('input').prop('readonly', true);
        cardArea.find('.form-trade-card-home').addClass('full').find('.btn-add-area-trade').remove();
        for(const cardEl of cardArea.find('.form-trade-card-home:not(.traded)')){
            const alertEl = $(cardEl).find('.alert-status');
            const card_type = $(cardEl).find('[name="card_type"]').val();
            const card_money = $(cardEl).find('[name="card_money"]').val();
            const type_trade = $(cardEl).find('[name="type_trade"]').val();
            const card_serial = $(cardEl).find('[name="card_serial"]').val().trim();
            const card_number = $(cardEl).find('[name="card_number"]').val().trim();
            const param = { card_type, card_money, card_serial, card_number, type_trade }

            alertEl.removeClass('d-none');
            await new Promise(function(resolve) {
                const request = Request.ajax('{{ route('trade-card.post.ajax') }}', param, function(result){
                    alertEl.removeClass('alert-info')
                    if(result.success === false) {
                        alertEl.addClass('alert-danger');
                        hasError = true;
                    }else{
                        alertEl.addClass('alert-success');
                        $(cardEl).find('select').prop('disabled', true);
                        $(cardEl).find('input').prop('readonly', true);
                        $(cardEl).addClass('traded');
                    }
                    alertEl.text(result.message);
                    return resolve(true);
                });
                request.fail(function(respon) {
                    hasError = true;
                    alertEl.removeClass('alert-info');
                    alertEl.addClass('alert-danger');
                    let errors = respon.responseJSON.errors;
                    if(errors == undefined) {
                        alertEl.text("Lỗi không xác định");
                        return resolve(false);
                    }
                    errors = Object.values(errors).map(err => '<li>'+err+'</li>');
                    alertEl.html($('<ul class="pl-4 mb-0" />').append(errors));
                    return resolve(false);
                })
            })
        }

        if(hasError) {
            alertify.alert('Error', "Có một vài thẻ gạch không thành công. Kiểm tra trên màn hình để biết thêm!");
            $('.alertify .ajs-header').addClass('alert-danger').removeClass('alert-success');
        }else{
            alertify.alert('Success', "Tất cả thẻ đã gạch thành công! Vui lòng kiểm tra lịch sử để xem trạng thái gạch thẻ!", function(){
                //window.location.href = '{{ route('trade-card.history') }}';
                window.location.reload();
            });
            $('.alertify .ajs-header').addClass('alert-success').removeClass('alert-danger');
        }
        @endif
    });

    $('[name="filter_card_type"]').on('change', function() {
        const val = $(this).val();
        const type = $('[name="filter_card_type"]').find('option[value="'+ val + '"]').attr('data-type');
        const filterMoney = $('[name="filter_money"]')
        filterMoney.empty().append('<option value="">Mệnh giá</option>');
        $.each(rates[type], function (index, rate) {
            const money = rate.price;
            filterMoney.append('<option value="' + money + '">' + App.setPriceFormat(money) + '</option>')
        });
    });

    $('.btn-filter').on('click', function(){
        const url = '{{ route('trade-card.history.filter') }}';
        const filter_card_type = $('[name="filter_card_type"]').val();
        const filter_money = $('[name="filter_money"]').val();
        const filter_status = $('[name="filter_status"]').val();
        const filter_from_date = $('[name="filter_from_date"]').val();
        const filter_to_date = $('[name="filter_to_date"]').val();
        Request.ajax(url, { filter_card_type, filter_money, filter_status, filter_from_date, filter_to_date }, function(result) {
            if(result.success) {
                $('.table-filter').empty().append(result.html)
            }
        })
    });

    $('.btn-filter-total').on('click', function(){
        const url = '{{ route('trade-card.total.filter') }}';
        const card_type = $('[name="filter_card_type_total"]').val();
        const start = $('[name="filter_from_date_total"]').val();
        const end = $('[name="filter_to_date_total"]').val();
        Request.ajax(url, { card_type, start, end }, function(result) {
            if(result.success) {
                $('.table-filter-total').empty().append(result.html)
            }else{
                alert(result.message);
            }
        })
    });

    $(function(){
        $("[data-date-picker]").datepicker({
            dateFormat : 'yy-mm-dd'
        });
    })
</script>
<script>
    window.CopyRowTrade = function () {
        const template = $('[data-id="template"]').clone();
        template.removeAttr('data-id');
        template.addClass('d-flex').removeClass('d-none');
        $("#row_save_position_add_trade").before(template);
    }
    window.RemoveRowTrade = function (el) {
        $(el).closest('.form-trade-card-home').remove();
    }
</script>
