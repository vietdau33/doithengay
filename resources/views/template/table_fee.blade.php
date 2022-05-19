@php($rates = getRates())
<div class="service_fee_table mt-4">
    <h4 class="font-weight-bold">Bảng phí đổi thẻ cào</h4>
    <ul class="nav nav-tabs service_fee_table_tab" role="tablist">
        @foreach($rates as $card => $rate)
            <li class="nav-item">
                <a class="nav-link" id="target_{{ $card }}_tab" data-toggle="tab" href="#target_{{ $card }}" role="tab" aria-controls="home">{{ ucfirst($card) }}</a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content service_fee_table_content">
        @foreach($rates as $card => $rate)
            <div class="tab-pane fade" id="target_{{ $card }}" role="tabpanel" aria-labelledby="target_{{ $card }}_tab">
                <table class="table table-bordered table-responsive-xl text-center">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col" colspan="2">Nhóm thành viên</th>
                        @foreach($rate as $money => $_r)
                            <th scope="col">Thẻ {{ number_format($money) }}đ</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @if(!logined() || user()->type_user == 'tongdaily')
                        <tr>
                            <th scope="col" colspan="2">Tổng Đại Lý</th>
                            @foreach($rate as $money => $_r)
                                <td>{{ $_r['rate_tongdaily'] }}%</td>
                            @endforeach
                        </tr>
                    @endif
                    @if(!logined() || user()->type_user == 'daily')
                        <tr>
                            <th scope="col" colspan="2">Đại Lý</th>
                            @foreach($rate as $money => $_r)
                                <td>{{ $_r['rate_daily'] }}%</td>
                            @endforeach
                        </tr>
                    @endif
                    @if(!logined() || user()->type_user == 'nomal')
                        <tr>
                            <th scope="col" rowspan="2">Thành viên</th>
                            <th scope="col">Gạch nhanh</th>
                            @foreach($rate as $money => $_r)
                                <td>{{ $_r['rate_use'] }}%</td>
                            @endforeach
                        </tr>
                        <tr>
                            <th scope="col">Gạch chậm</th>
                            @foreach($rate as $money => $_r)
                                <td>{{ $_r['rate_slow'] }}%</td>
                            @endforeach
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</div>
<script>
    $('.service_fee_table_tab').find('.nav-item:first-child .nav-link').trigger('click');
</script>
