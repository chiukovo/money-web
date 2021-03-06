@extends('admin.layouts.app')

@section('content')
<link rel="stylesheet" type="text/css" href="/assets/libs/bootstrap-datetimepicker/dist/css/bootstrap-datetimepicker.min.css">

<div class="page-breadcrumb">
  <div class="row">
    <div class="col-12 d-flex no-block align-items-center">
      <h4 class="page-title">
        前台會員帳號{{ $word }}
      </h4>
      <div class="ml-auto text-right">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin">首頁</a></li>
            <li class="breadcrumb-item"><a href="/admin">前台會員列表</a></li>
            <li class="breadcrumb-item active" aria-current="page">前台會員{{ $word }}</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
</div>
<div id="app" class="container-fluid">
  <div class="row">
    <div class="col-12">
      <form id="form">
        <div class="card">
          <div class="card-body">
            <div class="form-group row">
              <label class="col-md-1 m-t-15">會員帳號<span class="text-danger">*</span></label>
              <div class="col-sm-12 col-md-10 col-lg-4">
                @if($account != '')
                <input type="text" name="account" class="form-control" value="{{ $account }}" readonly>
                @else
                <input type="text" name="account" class="form-control" value="{{ $account }}">
                @endif
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-1 m-t-15">密碼<span class="text-danger">*</span></label>
              <div class="col-sm-12 col-md-10 col-lg-4">
                <input type="password" name="password" class="form-control">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-1 m-t-15">重複輸入密碼<span class="text-danger">*</span></label>
              <div class="col-sm-12 col-md-10 col-lg-4">
                <input type="password" name="re_password" class="form-control">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-1 m-t-15">email</label>
              <div class="col-sm-12 col-md-10 col-lg-4">
                <input type="text" name="email" class="form-control" value="{{ $email }}">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-1 m-t-15">推薦碼<span class="text-danger">*</span></label>
              <div class="col-sm-12 col-md-10 col-lg-4">
                <input type="text" name="code" class="form-control" value="{{ $code }}">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-1 m-t-15">姓名<span class="text-danger">*</span></label>
              <div class="col-sm-12 col-md-10 col-lg-4">
                <input type="text" name="name" class="form-control" value="{{ $name }}">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-1 m-t-15">暱稱</label>
              <div class="col-sm-12 col-md-10 col-lg-4">
                <input type="text" name="nickname" class="form-control" value="{{ $nickname }}">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-1 m-t-15">手機<span class="text-danger">*</span></label>
              <div class="col-sm-12 col-md-10 col-lg-4">
                <input type="text" name="phone" class="form-control" value="{{ $phone }}">
              </div>
            </div>
          </div>
          <div class="border-top">
            <div class="card-body">
              <button id="save" class="btn btn-primary">儲存</button>
            </div>
          </div>
          <input type="hidden" name="id" value="{{ $id }}">
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(function() {
    $('#save').click(function(e) {
      e.preventDefault();
      const data = $('#form').serialize();
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/admin/web/user/doEdit',
        data: data,
        type: 'POST',
        success: function(res) {
          if (res.status == 'success') {
            alert('新增/編輯成功');
            location.reload();
          } else {
            toastr.warning(res.msg, '訊息');
          }
        }
      });
    });
  });
</script>
@endsection