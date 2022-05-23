<table class="table table-striped table-bordered table-responsive-md">
    <thead class="table-dark">
    <tr>
        <th scope="row">#</th>
        <th scope="row">Thời gian</th>
        <th scope="row">Loại thẻ</th>
        <th scope="row">Mệnh giá</th>
		<th scope="col">Số lượng</th>
        <th scope="row">Trạng thái</th>
		<th scope="row">Mua</th>
		<th scope="row">Message</th>
        <th scope="row">Chiết khẩu (%)</th>
        <th scope="row">Tổng tiền</th>
    </tr>
    </thead>
    <tbody>
    @foreach($histories as $history)
        <tr>
            <td>
                @if($history->status === 2)
                    <a href="{{ route('list-card', ['hash' => $history->store_hash]) }}" class="text-decoration-none">Xem thẻ</a>
                @endif
            </td>
            <td style="min-width: 120px;">{{ date('H:i d-m-Y', strtotime($history->created_at)) }}</td>
            <td>{{ ucfirst($history->card_buy) }}</td>
            <td>{{ number_format($history->money_buy) }}</td>
			<td>{{ $history->quantity }}</td>
            <td>{!! $history->getStatus() !!}</td>
			<td>{{ $history->type_buy }}</td>
			<td>{{ $history->message }}</td>
            <td>{{ $history->rate_buy }}%</td>
            <td>{{ number_format($history->money_after_rate) }}đ</td>
        </tr>
    @endforeach
    @if($histories->count() <= 0)
        <tr>
            <td colspan="9" class="text-center">Không có dữ liệu</td>
        </tr>
    @endif
    </tbody>
</table>
