<table class="table table-striped table-bordered table-responsive-md">
    <thead class="table-dark">
    <tr>
        <th scope="row">#</th>
        <th scope="row">Thời gian</th>
        <th scope="row">Loại thẻ</th>
        <th scope="row">Mệnh giá</th>
		<th scope="row">Giá Thật</th>
        <th scope="row">Trạng thái</th>
		<th scope="row">Mua</th>
        <th scope="row">Chiết khẩu</th>
        <th scope="row">Tiền nhận</th>
        <th scope="row">Hoàn thành</th>
        <th scope="row">Mã thẻ</th>
    </tr>
    </thead>
    <tbody>
    @php($stt = 1)
    @foreach($histories as $history)
        <tr>
            <td>{{ $stt++ }}</td>
            <td style="min-width: 120px;">{{ date('H:i d-m-Y', strtotime($history->created_at)) }}</td>
            <td>{{ $history->getNameTelco() }}</td>
            <td>{{ number_format($history->card_money) }}</td>
			<td>{{ number_format($history->valueprices) }}</td>
            <td>{!! $history->getStatusHtml() !!}</td>
			<td>{{ $history->type_trade }}</td>
            <td>{{ $history->rate_use ?? 0 }}%</td>
            <td>{{ number_format($history->money_real) }}đ</td>
            <td>{{ $history->completion }}</td>
            <td>{{ $history->card_number }}</td>
        </tr>
    @endforeach
    @if($histories->count() <= 0)
        <tr>
            <td colspan="9" class="text-center">Không có dữ liệu</td>
        </tr>
    @endif
    </tbody>
</table>
