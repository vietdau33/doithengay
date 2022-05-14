@extends('admin_layout')
@section('contents')
    <div class="content">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Danh sách thông báo ở màn hình chính</h3>
                <button class="btn btn-primary btn-add-notification" data-toggle="modal"
                        data-target="#modal_add_new_notification">Thêm mới
                </button>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-striped table-vcenter text-center">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Alias</th>
                            <th>Nội dung</th>
                            <th>Ngày tạo</th>
                            <th>Kích hoạt</th>
                            <th>Hành động</th>
                        </tr>
                        </thead>
                        <tbody id="tbody-notification" data-max-row="{{ $notification->count() }}">
                        <tr id="template_tr_data" class="d-none">
                            <td data-item="stt"></td>
                            <td style="width: 250px; word-break: break-all" data-item="alias"></td>
                            <td data-item="content"></td>
                            <td data-item="created_at"></td>
                            <td data-item="active">
                                <label class="toggle" for="">
                                    <input
                                        type="checkbox"
                                        class="toggle__input toggle__input__active"
                                        data-alias=""
                                        id=""
                                        checked
                                    />
                                    <span class="toggle-track m-0">
                                            <span class="toggle-indicator">
                                                <span class="checkMark">
                                                    <svg viewBox="0 0 24 24" id="ghq-svg-check" role="presentation"
                                                         aria-hidden="true">
                                                        <path
                                                            d="M9.86 18a1 1 0 01-.73-.32l-4.86-5.17a1.001 1.001 0 011.46-1.37l4.12 4.39 8.41-9.2a1 1 0 111.48 1.34l-9.14 10a1 1 0 01-.73.33h-.01z"></path>
                                                    </svg>
                                                </span>
                                            </span>
                                        </span>
                                </label>
                            </td>
                            <td data-item="action">
                                <button class="btn btn-danger btn-delete-notif" data-alias="">Xóa</button>
                            </td>
                        </tr>
                        @php($stt = 1)
                        @foreach($notification as $notif)
                            <tr>
                                <td>{{ $stt++ }}</td>
                                <td style="width: 250px; word-break: break-all">{{ $notif->alias }}</td>
                                <td>{{ $notif->content }}</td>
                                <td>{{ date('d/m/Y', strtotime($notif->created_at)) }}</td>
                                <td>
                                    <label class="toggle" for="active_notification_{{ $notif->alias }}">
                                        <input
                                            type="checkbox"
                                            class="toggle__input toggle__input__active"
                                            data-alias="{{ $notif->alias }}"
                                            id="active_notification_{{ $notif->alias }}"
                                            {{ $notif->active === 1 ? 'checked' : '' }}
                                        />
                                        <span class="toggle-track m-0">
                                                <span class="toggle-indicator">
                                                    <span class="checkMark">
                                                        <svg viewBox="0 0 24 24" id="ghq-svg-check" role="presentation"
                                                             aria-hidden="true">
                                                            <path
                                                                d="M9.86 18a1 1 0 01-.73-.32l-4.86-5.17a1.001 1.001 0 011.46-1.37l4.12 4.39 8.41-9.2a1 1 0 111.48 1.34l-9.14 10a1 1 0 01-.73.33h-.01z"></path>
                                                        </svg>
                                                    </span>
                                                </span>
                                            </span>
                                    </label>
                                </td>
                                <td>
                                    <button class="btn btn-danger btn-delete-notif" data-alias="{{ $notif->alias }}">
                                        Xóa
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        @if($notification->count() <= 0)
                            <tr id="no_have_notification">
                                <td colspan="5">Không có thông báo nào</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_add_new_notification" tabindex="-1" role="dialog"
         aria-labelledby="modal_add_new_notification" aria-hidden="true">
        <div class="modal-dialog modal-dialog-fromright" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Thêm một thông báo mới</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="form-group">
                            <label for="content_notification_new">Nội dung:</label>
                            <textarea type="text" class="form-control" placeholder="Somthing"
                                      id="content_notification_new"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-alt-success btn-submit-add">Thêm</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const $textarea = $("#content_notification_new");
        autosize($textarea);
        $('#modal_add_new_notification').on('hide.bs.modal', function () {
            $textarea.val('');
            autosize.update($textarea)
        });
        $('#modal_add_new_notification .btn-submit-add').on('click', function () {
            const url = '{{ route('admin.notification.save_notif') }}';
            const content_new = $("#content_notification_new").val().trim();
            const template = $("#template_tr_data").clone();
            Request.ajax(url, {content_new}, function (result) {
                if (!result.success) {
                    return alert(result.message);
                }
                alertify.success(result.message);

                const tbody = $("#tbody-notification");
                const maxRow = parseInt(tbody.attr('data-max-row')) + 1;

                tbody.attr('data-max-row', maxRow);
                template.removeAttr('id').removeAttr('class');

                template.find('[data-item="stt"]').text(maxRow);
                template.find('[data-item="alias"]').text(result.datas.alias);
                template.find('[data-item="content"]').text(result.datas.content);
                template.find('[data-item="created_at"]').text(result.datas.created_at);
                template.find('[data-item="action"] .btn-delete-notif').attr('data-alias', result.datas.alias)

                const activeEl = template.find('[data-item="active"]');
                const idActiveEl = 'active_notification_' + result.datas.alias;
                activeEl.find('label').attr('for', idActiveEl);
                activeEl.find('input').attr('id', idActiveEl);
                activeEl.find('input').attr('data-alias', result.datas.alias);

                tbody.append(template);
                $("#no_have_notification").remove();
                $('#modal_add_new_notification').modal('hide');
            });
        });
        $("#tbody-notification").on('change', ".toggle__input__active", function () {
            const alias = $(this).attr('data-alias');
            const self = this;
            Request.ajax('{{ route('admin.notification.change_status') }}', {alias}, function (result) {
                if (!result.success) {
                    alert(result.message);
                    return window.location.reload();
                }
                $(self).prop('checked', result.active);
            });
        });
        $("#tbody-notification").on('click', '.btn-delete-notif', function () {
            if (!confirm('Chắn chắn xóa thông báo này?')) {
                return false;
            }
            const alias = $(this).attr('data-alias');
            const self = this;
            Request.ajax('{{ route('admin.notification.delete') }}', {alias}, function (result) {
                if (!result.success) {
                    alert(result.message);
                    return window.location.reload();
                }
                $(self).closest('tr').remove();
                $("#tbody-notification").find("tr:not([id='template_tr_data'])").each(function (index, el) {
                    $(this).find('td:first-child').text(index + 1);
                });
            });
        });
    </script>
@endsection
