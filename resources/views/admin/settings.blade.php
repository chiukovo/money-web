@extends('admin.layouts.app')

@section('content')
<link rel="stylesheet" type="text/css" href="/assets/libs/bootstrap-datetimepicker/dist/css/bootstrap-datetimepicker.min.css">

<div class="page-breadcrumb">
  <div class="row">
    <div class="col-12 d-flex no-block align-items-center">
      <h4 class="page-title">
        系統設定
      </h4>
      <div class="ml-auto text-right">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin">首頁</a></li>
            <li class="breadcrumb-item active" aria-current="page">系統設定</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
</div>
<div id="app" class="container-fluid">
  <div class="row">
    <div class="col-12">
      <form enctype="multipart/form-data" id="form">
        <div class="card">
          <div class="card-body">
            <div class="form-group row">
              <label class="col-md-2 m-t-15">預設代理碼</label>
              <div class="col-sm-12 col-md-10 col-lg-4">
                <input type="text" name="default_code" class="form-control" value="{{ $code }}">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 m-t-15"></label>
              <div class="col-sm-12 col-md-10 col-lg-4">
                <p>
                  代理碼範例: 如使用代碼為zz1234
                  網址為: <a href="https://e63.tw?t=zz1234">https://e63.tw?t=zz1234</a>
                </p>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 m-t-15">Android下載網址</label>
              <div class="col-sm-12 col-md-10 col-lg-4">
                <input type="text" name="android_game_download_url" class="form-control" value="{{ $au }}">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 m-t-15">ios下載網址</label>
              <div class="col-sm-12 col-md-10 col-lg-4">
                <input type="text" name="ios_game_download_url" class="form-control" value="{{ $iu }}">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 m-t-15">下載教學網址</label>
              <div class="col-sm-12 col-md-10 col-lg-4">
                <input type="text" name="download_teach_url" class="form-control" value="{{ $du }}">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 m-t-15">跑馬燈文字</label>
              <div class="col-sm-12 col-md-10 col-lg-4">
                <input type="text" name="marquee_word" class="form-control" value="{{ $marquee }}">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 m-t-15">活動網址</label>
              <div class="col-sm-12 col-md-10 col-lg-4">
                <input type="text" name="activity_url" class="form-control" value="{{ $act }}">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 m-t-15">黑名單手機號</label>
              <div class="col-sm-12 col-md-10 col-lg-4">
                <input type="text" name="disabled_phone" class="form-control" value="{{ $dp }}">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 m-t-15"></label>
              <div class="col-sm-12 col-md-10 col-lg-4">
                <p>
                  可新增多組 請用逗號隔開<br>
                  範例: 0912345678,0911111221,0933333333
                </p>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 m-t-15">活動圖片上傳</label>
              <div class="col-sm-12 col-md-10 col-lg-4">
                <div class="form-group">
                  <label for="act_file">限制jpg或png</label>
                  <input type="file" class="form-control-file" id="act_file" name="act_file">
                  <div style="margin: 20px 0">
                    @if($fileName != '')
                    <img src="/storage/{{ $fileName }}" alt="" style="width: 150px">
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="border-top">
            <div class="card-body">
              <button id="save" class="btn btn-primary">儲存</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(function() {
    $('#save').click(function(e) {
      e.preventDefault();
      let data = $('#form')[0];
      let formData = new FormData(data);

      console.log(formData)
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        contentType: false,
        cache: false,
        processData: false,
        url: '/admin/settings',
        data: formData,
        type: 'POST',
        success: function(res) {
          if (res.status == 'success') {
            alert('編輯成功');
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