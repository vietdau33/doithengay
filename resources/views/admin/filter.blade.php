<div class="block-filter d-flex">
    <input type="text" class="form-control mr-2" name="username" placeholder="Tài khoản" value="{{ request()->username ?? '' }}">
    <input type="text" style="max-width: 200px" name="filter_from_date" class="form-control mr-2" value="{{ request()->filter_from_date ?? date('Y-m-d') }}" data-date-picker>
    <input type="text" style="max-width: 200px" name="filter_to_date" class="form-control mr-2" value="{{ request()->filter_to_date ?? date('Y-m-d') }}" data-date-picker>
    <button class="btn btn-success btn-filter" style="min-width: 150px;">Lọc dữ liệu</button>
</div>
<hr>
<script>
    document.querySelector('.btn-filter').addEventListener('click', function(){
        const username = $('[name="username"]').val().trim();
        const filter_from_date = $('[name="filter_from_date"]').val();
        const filter_to_date = $('[name="filter_to_date"]').val();
        let href = location.origin + location.pathname + '?username=' + username
        href += '&filter_from_date=' + filter_from_date;
        href += '&filter_to_date=' + filter_to_date;
        location.href = href;
    });
</script>
