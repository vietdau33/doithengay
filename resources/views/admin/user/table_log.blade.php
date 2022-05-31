<!-- Nav tabs -->
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#logs_activity">Hoạt động</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#trade_card">Đổi thẻ</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#trade_total">Thống kê đổi thẻ</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#buy_card">Mua thẻ</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#recharge">Nạp tiền</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#withdraw">Rút tiền</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#transfer">Chuyển tiền</a>
    </li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <div class="tab-pane container-fluid active" id="logs_activity" data-tab="activity">
        @include('admin.user.logs.activity', ['logs' => $histories['activity']])
    </div>
    <div class="tab-pane container-fluid fade" id="trade_card" data-tab="trade_card">
        @include('admin.user.logs.trade_card', ['logs' => $histories['trade_card']])
    </div>
    <div class="tab-pane container-fluid fade" id="trade_total" data-tab="trade_total">
        @include('admin.user.logs.trade_total', ['total' => $histories['trade_total']])
    </div>
    <div class="tab-pane container-fluid fade" id="buy_card" data-tab="buy_card">
        @include('admin.user.logs.buy_card', ['logs' => $histories['buy_card']])
    </div>
    <div class="tab-pane container-fluid fade" id="recharge" data-tab="recharge">
        @include('admin.user.logs.recharge', ['logs' => $histories['recharge']])
    </div>
    <div class="tab-pane container-fluid fade" id="withdraw" data-tab="withdraw">
        @include('admin.user.logs.withdraw', ['logs' => $histories['withdraw']])
    </div>
    <div class="tab-pane container-fluid fade" id="transfer" data-tab="transfer">
        @include('admin.user.logs.transfer', ['logs' => $histories['transfer']])
    </div>
</div>
