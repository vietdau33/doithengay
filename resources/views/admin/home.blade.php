@extends('admin_layout')
@section('style')
    <style>
        .w-50px{
            width: 50px;
        }
        .block-report {
            overflow: hidden;
            height: 45px;
        }
        .block-report .alert{
            cursor: pointer;
            font-size: 16px;
        }
    </style>
@endsection
@section('contents')
    <div class="content">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Dashboard</h3>
            </div>
            <div class="block-content">
                <div class="alert alert-warning">
                    <p class="m-0 font-weight-bold">Chú ý: Để tránh lãng phí băng thông, thông tin report sẽ được cập nhật sau mỗi 10 phút.</p>
                    <p class="m-0 font-weight-bold">Nếu muốn xem các thay đổi tức thì, hãy vào từng mục chi tiết ở menu!</p>
                </div>
                {{-- Bán thẻ --}}
                <div class="block-report mb-4">
                    <h5 class="alert alert-info mb-2">
                        <span>Report bán thẻ</span>
                        <i class="icon-up-down fa fa-angle-down float-right"></i>
                    </h5>
                    <table class="table table-hover table-vcenter table-bordered">
                        <thead>
                            <tr>
                                <th colspan="2">Nội dung</th>
                                <th class="text-center">Giá trị</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3">Bán chậm</td>
                            </tr>
                            @foreach($reports['buy_card']['slow'] as $key => $val)
                                <tr>
                                    <td class="w-50px"></td>
                                    <td>{{ getTextReport()[$key] }}</td>
                                    <td class="text-center">{{ $val }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3">Bán nhanh</td>
                            </tr>
                            @foreach($reports['buy_card']['fast'] as $key => $val)
                                <tr>
                                    <td class="w-50px"></td>
                                    <td>{{ getTextReport()[$key] }}</td>
                                    <td class="text-center">{{ $val }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- Đổi thẻ --}}
                <div class="block-report mb-4">
                    <h5 class="alert alert-info mb-2">
                        <span>Report đổi thẻ</span>
                        <i class="icon-up-down fa fa-angle-down float-right"></i>
                    </h5>
                    <table class="table table-hover table-vcenter table-bordered">
                        <thead>
                            <tr>
                                <th colspan="3">Nội dung</th>
                                <th class="text-center">Giá trị</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4">Trên Website</td>
                            </tr>
                            <tr>
                                <td class="w-50px"></td>
                                <td colspan="3">Bán chậm</td>
                            </tr>
                            @foreach($reports['trade_card']['nomal']['slow'] as $key => $val)
                                <tr>
                                    <td class="w-50px"></td>
                                    <td class="w-50px"></td>
                                    <td>{{ getTextReport()[$key] }}</td>
                                    <td class="text-center">{{ $val }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="w-50px"></td>
                                <td colspan="3">Bán nhanh</td>
                            </tr>
                            @foreach($reports['trade_card']['nomal']['fast'] as $key => $val)
                                <tr>
                                    <td class="w-50px"></td>
                                    <td class="w-50px"></td>
                                    <td>{{ getTextReport()[$key] }}</td>
                                    <td class="text-center">{{ $val }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4">API</td>
                            </tr>
                            <tr>
                                <td class="w-50px"></td>
                                <td colspan="3">Bán chậm</td>
                            </tr>
                            @foreach($reports['trade_card']['api']['slow'] as $key => $val)
                                <tr>
                                    <td class="w-50px"></td>
                                    <td class="w-50px"></td>
                                    <td>{{ getTextReport()[$key] }}</td>
                                    <td class="text-center">{{ $val }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="w-50px"></td>
                                <td colspan="3">Bán nhanh</td>
                            </tr>
                            @foreach($reports['trade_card']['api']['fast'] as $key => $val)
                                <tr>
                                    <td class="w-50px"></td>
                                    <td class="w-50px"></td>
                                    <td>{{ getTextReport()[$key] }}</td>
                                    <td class="text-center">{{ $val }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- Hóa đơn --}}
                <div class="block-report mb-4">
                    <h5 class="alert alert-info mb-2">
                        <span>Report thanh toán hóa đơn</span>
                        <i class="icon-up-down fa fa-angle-down float-right"></i>
                    </h5>
                    <table class="table table-hover table-vcenter table-bordered">
                        <thead>
                        <tr>
                            <th>Nội dung</th>
                            <th class="text-center">Giá trị</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($reports['bill'] as $key => $val)
                                <tr>
                                    <td>{{ getTextReport()[$key] }}</td>
                                    <td class="text-center">{{ $val }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- rút tiền --}}
                <div class="block-report mb-4">
                    <h5 class="alert alert-info mb-2">
                        <span>Report rút tiền</span>
                        <i class="icon-up-down fa fa-angle-down float-right"></i>
                    </h5>
                    <table class="table table-hover table-vcenter table-bordered">
                        <thead>
                        <tr>
                            <th>Nội dung</th>
                            <th class="text-center">Giá trị</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reports['withdraw'] as $key => $val)
                            <tr>
                                <td>{{ getTextReport()[$key] }}</td>
                                <td class="text-center">{{ $val }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('.block-report > h5.alert').on('click', function(){
            const self = $(this).parent();
            const icon = self.find('.icon-up-down');
            if(self.hasClass('opended')) {
                self.removeClass('opended');
                icon.removeClass('fa-angle-up');
                icon.addClass('fa-angle-down');
                self.animate({'height': '45px'}, 300);
                return;
            }

            const table = self.find('table');
            self.addClass('opended');
            icon.removeClass('fa-angle-down');
            icon.addClass('fa-angle-up');
            self.animate({ height: (table.innerHeight() + 45) + 'px'}, {
                duration: 300,
                complete: function () {
                    self.css('height', '100%');
                }
            })
        });
    </script>
@endsection
