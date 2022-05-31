<div class="table-responsive">
    <table class="table table-striped table-bordered table-responsive-md text-center">
        <thead class="table-dark">
        <tr>
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
            <tr>
                <td>{{ $total['card'] }}</td>
                <td>{{ number_format($total['money']) }}</td>
                <td>{{ $total['success'] }} ({{ number_format($total['success_price']) }})</td>
                <td>{{ $total['error'] }} ({{ number_format($total['error_price']) }})</td>
                <td>{{ $total['half'] }} ({{ number_format($total['half_price']) }})</td>
                <td>{{ $total['pending'] }} ({{ number_format($total['pending_price']) }})</td>
                <td>{{ number_format($total['real']) }}</td>
            </tr>
        </tbody>
    </table>    
</div>
