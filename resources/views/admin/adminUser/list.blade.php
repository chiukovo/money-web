@extends('admin.layouts.app')

@section('content')

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">後台帳號列表</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="text-right m-t-10 m-b-10">
                <a href="/admin/user/create" class="btn btn-info">新增帳號</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">後台帳號</h5>
                    <form action="">
                        <div class="form form-row align-items-center m-b-10">
                            <div class="col-auto text-xs">帳號</div>
                            <div class="col-auto">
                                <label class="text-xs sr-only">帳號</label>
                                <input name="account" type="text" class="form-control" placeholder="請輸入帳號" value="{{ $account }}">
                            </div>
                            <div class="col-auto text-xs">名稱</div>
                            <div class="col-auto">
                                <label class="text-xs sr-only">名稱</label>
                                <input name="name" type="text" class="form-control" placeholder="請輸入名稱" value="{{ $name }}">
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-info">搜尋</button>
                                <button type="button" class="btn" onclick="location.href='/admin'">清除搜尋條件</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>名稱</th>
                                    <th>帳號</th>
                                    <th>創建日期</th>
                                    <th>修改日期</th>
                                    <th>功能</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($adminData as $data)
                                <tr>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->account }}</td>
                                    <td>{{ $data->created_at }}</td>
                                    <td>{{ $data->updated_at }}</td>
                                    <td>
                                        <a href="/admin/user/edit?id={{ $data->id }}">
                                            <button type="button" class="btn btn-success btn-sm">編輯</button>
                                        </a>
                                        @if($data->id != 1)
                                        <button type="button" class="btn btn-danger btn-sm" onclick="del('{{ $data->id }}', '{{ $data->account }}')">移除</button>
                                        @endif
                                    </td>
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