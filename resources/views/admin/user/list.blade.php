@extends('admin_layout')
@php($isActivePage = $action == 'active')
@section('contents')
    <div class="content">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    Danh sách thành viên {{ $isActivePage ? 'đang hoạt động' : 'đã bị chặn' }}!
                </h3>
            </div>
            <div class="block-content">
                <div class="block-filter d-flex">
                    <input type="text" class="form-control mr-2" name="account" placeholder="Tài khoản, email, số điện thoại">
                    <input type="text" style="max-width: 200px" name="filter_from_date" class="form-control mr-2" value="{{ date('Y-m-d') }}" data-date-picker>
                    <input type="text" style="max-width: 200px" name="filter_to_date" class="form-control mr-2" value="{{ date('Y-m-d') }}" data-date-picker>
                    <button class="btn btn-success btn-filter" data-type-filter="{{ $isActivePage ? 'active' : 'block' }}" style="min-width: 150px;">Lọc dữ liệu</button>
                </div>
                <hr>
                <div class="table-responsive area-result-filter">
                    @include('admin.user.table_user')
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-change-money" tabindex="-1" role="dialog" aria-labelledby="modal-popin" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popin" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Cộng tiền cho <b class="username"></b></h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <form action="" method="POST" id="form-plus-money-user">
                            <input type="hidden" name="username" value="">
                            <div class="alert alert-info">Nếu muốn trừ tiền, thêm dấu - trước số tiền. Ví dụ: -10000</div>
                            <div class="form-group">
                                <label for="money_plus">Số tiền</label>
                                <input type="text" class="form-control" id="money_plus" name="money_plus" placeholder="Số tiền cộng">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-alt-success btn-submit-plus-money">
                        <i class="fa fa-check"></i> Đồng ý
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-view-logs" tabindex="-1" role="dialog" aria-labelledby="modal-popin" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popin modal-xl" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Xem thông tin logs của <b class="username"></b></h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <p class="row-loading">Đang lấy data</p>
                        <div class="area-data"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        let href = '{{ route('plus-money') }}';
        let model = $('#modal-change-money');
        let _form = $('#form-plus-money-user');
        $('.area-result-filter').on('click', '.btn-plus-money', function(e){
            e.preventDefault();
            let username = $(this).attr('data-username');
            model.find('.username').text(username);
            model.find('[name="username"]').attr('value', username);
            model.find('[name="money_plus"]').val('');
            model.modal('show');
        });
        $('.btn-submit-plus-money').on('click', function(e){
            e.preventDefault();
            let formData = new FormData(_form[0]);
            Request.ajax(href, formData, function(result){
                model.modal('hide');
                if(!result.success) {
                    alertify.alert('Error', result.message)
                    $('.alertify .ajs-header').addClass('alert-danger');
                    return false;
                }

                alertify.alert('Success', result.message)
                $('.alertify .ajs-header').addClass('alert-success');

                let newMoney = result.data.money;
                let username = model.find('[name="username"]').val();
                $('[data-username="' + username + '"]').closest('tr').find('.row-money').text(newMoney);
                return false;
            });
        });
        $('#modal-view-logs').on('click', 'a.page-link', function(e){
            e.preventDefault();
            const modal = $('#modal-view-logs');
            const page = $(this).attr('data-page');
            Request.ajax('{{ route('admin.getlog.user') }}', {id: window.idUser, page}, function(result){
                modal.find('.row-loading').hide(100);
                modal.find('.area-data').html(result.html).show(100);
            });
        })
        $('.area-result-filter').on('click', '.btn-view-logs', function(){
            const idUser = $(this).attr('data-id');
            const username = $(this).attr('data-username');
            const modal = $('#modal-view-logs');
            window.idUser = idUser;
            modal.find('.username').text(username);
            modal.find('.row-loading').show();
            modal.find('.area-data').empty().hide();
            modal.modal();
            Request.ajax('{{ route('admin.getlog.user') }}', {id: idUser}, function(result){
                modal.find('.row-loading').hide(100);
                modal.find('.area-data').html(result.html).show(100);
            });
        });
        @if($isActivePage)
        $('.area-result-filter').on('change', '.select-change-user-type', function(){
            const newValue = $(this).val();
            const username = $(this).attr('data-username');
            Request.ajax('{{ route('admin.changelevel.user') }}', {newValue, username}, function(result) {
                if(result.success == false) {
                    alert(result.message);
                    window.location.reload();
                }
            })
        });
        @endif
        $('.btn-filter').on('click', function(){
            const type_filter = $(this).attr('data-type-filter');
            const account = $('[name="account"]').val().trim();
            const filter_from_date = $('[name="filter_from_date"]').val().trim();
            const filter_to_date = $('[name="filter_to_date"]').val().trim();
            const params = { type_filter, account, filter_to_date, filter_from_date };
            Request.ajax('{{ route('admin.filter-user') }}', params, function(result){
                $('.area-result-filter').empty().html(result.html);
            });
        });
    </script>
@endsection
