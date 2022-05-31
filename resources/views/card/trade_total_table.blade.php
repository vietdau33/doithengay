<table class="table table-striped table-bordered table-responsive-md text-center">
    <thead class="table-dark">
    <tr>
        <th scope="row">#</th>
        <th scope="row">Ngày</th>
        <th scope="row">Tổng số thẻ</th>
        <th scope="row">Tổng mệnh giá</th>
        <th scope="row">Tổng thẻ đúng</th>
		<th scope="row">Tổng thẻ sai</th>
        <th scope="row">Tổng sai mệnh giá</th>
        <th scope="row">Tổng thẻ chờ</th>
        <th scope="row">Thực nhận</th>
    </tr>
    </thead>
    <tbody>
    @php($stt = 1)
    @foreach($totals as $date => $total)
        <tr>
            <td>{{ $stt++ }}</td>
            <td style="min-width: 130px">{{ $date }}</td>
            <td>{{ $total['card'] }}</td>
            <td>{{ number_format($total['money']) }}</td>
            <td>{{ $total['success'] }} ({{ number_format($total['success_price']) }})</td>
            <td>{{ $total['error'] }} ({{ number_format($total['error_price']) }})</td>
            <td>{{ $total['half'] }} ({{ number_format($total['half_price']) }})</td>
            <td>{{ $total['pending'] }} ({{ number_format($total['pending_price']) }})</td>
            <td>{{ number_format($total['real']) }}</td>
        </tr>
    @endforeach
    @if(count($totals) == 0)
        <tr>
            <td colspan="9" class="text-center">Không có dữ liệu</td>
        </tr>
    @endif
    </tbody>
</table>
