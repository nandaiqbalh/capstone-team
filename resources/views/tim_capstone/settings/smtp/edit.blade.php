@extends('tim_capstone.base.app')

@section('title')
    Email SMTP
@endsection

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header"></section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title">
                                Email SMTP
                            </h5>
                        </div>
                    </div>
                </div>
                <form action="{{ url('/admin/settings/smtp/edit_process') }}" method="post" autocomplete="off">
                    <div class="card-body">
                        <!-- notification -->
                        @include("template.notification")

                        {{ csrf_field()}}
                        <input type="hidden" name="email_id" value="{{ $smtp->email_id }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="email_name" value="{{ $smtp->email_name }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email<span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email_address" value="{{ $smtp->email_address }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Host<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="smtp_host" value="{{ $smtp->smtp_host }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Port<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="smtp_port" value="{{ $smtp->smtp_port }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Username<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="smtp_username" value="{{ $smtp->smtp_username }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Password<span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="smtp_password" value="{{ $smtp->smtp_password }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Smtp Digunakan? <span class="text-danger">*</span></label>
                                    <select class="form-control" name="use_smtp" required>
                                        <option value="1" @if($smtp->use_smtp == '1' ) selected @endif>Ya</option>
                                        <option value="0" @if($smtp->use_smtp == '0' ) selected @endif>Tidak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Perlu Authorization? <span class="text-danger">*</span></label>
                                    <select class="form-control" name="use_authorization" required>
                                        <option value="1" @if($smtp->use_authorization == '1' ) selected @endif>Ya</option>
                                        <option value="0" @if($smtp->use_authorization == '0' ) selected @endif>Tidak</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection
