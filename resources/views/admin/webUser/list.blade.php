@extends('admin.layouts.app')

@section('content')

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">前台會員帳號列表</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="text-right m-t-10 m-b-10">
                <a href="/admin/web/user/create" class="btn btn-info">新增帳號</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">前台會員帳號</h5>
                    <form action="">
                        <div class="form form-row align-items-center m-b-10">
                            <div class="col-auto text-xs">帳號</div>
                            <div class="col-auto">
                                <label class="text-xs sr-only">帳號</label>
                                <input name="account" type="text" class="form-control" placeholder="請輸入帳號" value="{{ $account }}">
                            </div>
                            <div class="col-auto text-xs">手機</div>
                            <div class="col-auto">
                                <label class="text-xs sr-only">手機</label>
                                <input name="phone"" type=" text" class="form-control" placeholder="請輸入手機" value="{{ $phone }}">
                            </div>
                            <div class="col-auto text-xs">推廣碼</div>
                            <div class="col-auto">
                                <label class="text-xs sr-only">推廣碼</label>
                                <input name="code"" type=" text" class="form-control" placeholder="請輸入推廣碼" value="{{ $code }}">
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-info">搜尋</button>
                                <button type="button" class="btn" onclick="location.href='/admin/web/user/list'">清除搜尋條件</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>帳號</th>
                                    <th>暱稱</th>
                                    <th>手機</th>
                                    <th>推廣代碼</th>
                                    <th>真實姓名</th>
                                    <th>email</th>
                                    <th>註冊日期</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($webUserData as $data)
                                <tr>
                                    <td>{{ $data->account }}</td>
                                    <td>{{ $data->nickname }}</td>
                                    <td>{{ $data->phone }}</td>
                                    <td>{{ $data->code }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->email }}</td>
                                    <td>{{ $data->created_at }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function del(id, account) {
        var yes = confirm('確定要刪除帳號 ' + account + ' 嗎？');

        if (yes) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/user/delete',
                data: {
                    id: id
                },
                type: 'DELETE',
                success: function(res) {
                    if (res.status == 'success') {
                        location.reload();
                    } else {
                        toastr.warning(res.msg, '訊息');
                    }
                }
            });
        } else {
            return
        }
    }
</script>
@endsection