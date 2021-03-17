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
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">前台會員帳號</h5>
                    <form action="">
                        <div class="form form-row align-items-center m-b-10">
                            <div class="col-auto text-xs">名稱</div>
                            <div class="col-auto">
                                <label class="text-xs sr-only">名稱</label>
                                <input name="name" type="text" class="form-control" placeholder="請輸入帳號" value="{{ $name }}">
                            </div>
                            <div class="col-auto text-xs">手機</div>
                            <div class="col-auto">
                                <label class="text-xs sr-only">手機</label>
                                <input name="phone"" type=" text" class="form-control" placeholder="請輸入手機" value="{{ $phone }}">
                            </div>
                            <div class="col-auto text-xs">vx</div>
                            <div class="col-auto">
                                <label class="text-xs sr-only">vx</label>
                                <input name="vx"" type=" text" class="form-control" placeholder="請輸入vx" value="{{ $vx }}">
                            </div>
                            <div class="col-auto text-xs">ip</div>
                            <div class="col-auto">
                                <label class="text-xs sr-only">ip</label>
                                <input name="ip"" type=" text" class="form-control" placeholder="請輸入ip" value="{{ $ip }}">
                            </div>
                            <div class="col-auto text-xs">狀態</div>
                            <div class="col-auto">
                                <label class="text-xs sr-only">狀態</label>
                                <select name="status" class="custom-select">
                                    <option value="">全部</option>
                                    <option value="0" {{ $status == '0' ? 'selected' : '' }}>未聯絡</option>
                                    <option value="1" {{ $status == '1' ? 'selected' : '' }}>已聯絡</option>
                                    <option value="2" {{ $status == '2' ? 'selected' : '' }}>來亂的</option>
                                </select>
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
                                    <th>手機</th>
                                    <th>vx</th>
                                    <th>ip</th>
                                    <th>訊息</th>
                                    <th>狀態</th>
                                    <th>註冊日期</th>
                                    <th>功能</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($webUserData as $data)
                                <tr>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->phone }}</td>
                                    <td>{{ $data->vx }}</td>
                                    <td>{{ $data->ip }}</td>
                                    <td>{{ $data->msg }}</td>
                                    <td>
                                        <select class="custom-select" onchange="updateStatus(this, '{{ $data->id }}')">
                                            <option value="0" {{ $data->status == '0' ? 'selected' : ''}}>未聯絡</option>
                                            <option value="1" {{ $data->status == '1' ? 'selected' : ''}}>已聯絡</option>
                                            <option value="2" {{ $data->status == '2' ? 'selected' : ''}}>來亂的</option>
                                        </select>
                                    </td>
                                    <td>{{ $data->created_at }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="del('{{ $data->id }}', '{{ $data->name }}')">移除</button>
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
                url: '/admin/web/user/delete',
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

    function updateStatus(obj, id) {
        const status = obj.value

        if (!parseInt(status) && !parseInt(id)) {
            alert('not int')
            return false
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/admin/web/user/update/status',
            data: {
                id: id,
                status: status
            },
            type: 'POST',
            success: function(res) {
                console.log(res)
            }
        });
    }
</script>
@endsection