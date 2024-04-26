@extends('tim_capstone.base.app')

<!-- inject helper date indonesia -->
@inject('dtid', 'App\Helpers\DateIndonesia')

@section('title')
    Pengaturan
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Logs</h5>

        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills mb-3 " role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home"
                            aria-selected="true">
                            <i class="tf-icons bx bx-bug-alt"></i> Sistem
                        </button>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('admin/settings/logs/login') }}" type="button" class="nav-link"
                            data-bs-target="#navs-pills-justified-profile" aria-controls="navs-pills-justified-profile"
                            aria-selected="false">
                            <i class="tf-icons bx bx-log-in"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('admin/settings/logs/login-attempt') }}" type="button" class="nav-link"
                            data-bs-target="#navs-pills-justified-messages" aria-controls="navs-pills-justified-messages"
                            aria-selected="false">
                            <i class="tf-icons bx bx-ghost"></i> Percobaan Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('admin/settings/logs/reset-password') }}" type="button" class="nav-link"
                            data-bs-target="#navs-pills-justified-messages" aria-controls="navs-pills-justified-messages"
                            aria-selected="false">
                            <i class="tf-icons bx bx-lock"></i> Reset Password
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- notification -->
        @include('template.notification')

        <div class="card">
            <h5 class="card-header">Log Sistem</h5>

            <div class="card-body">

                <div class="row mb-2">
                    <div class="col-md-12">
                        <form class="form-inline" action="{{ url('/admin/settings/logs/search') }}" method="get"
                            autocomplete="off">
                            <div class="row">
                                <div class="col-auto mt-1">
                                    <input class="form-control mr-sm-2" type="date" name="date"
                                        value="{{ !empty($date) ? $date : date('Y-m-d') }}" max="{{ date('Y-m-d') }}"
                                        required>
                                </div>
                                <div class="col-auto mt-1">
                                    <button class="btn btn-outline-secondary ml-1" type="submit" name="action"
                                        value="search">
                                        <i class="bx bx-search-alt-2"></i>
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <br>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="25%">Timestamp</th>
                                <th width="10%">ENV</th>
                                <th width="10%">Type</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_logs->count() > 0)
                                @foreach ($rs_logs as $index => $log)
                                    <tr>
                                        <td class="text-center">
                                            {{ $log->timestamp }}
                                            <br>
                                            <small>
                                                <span
                                                    class="text-lowercase">{{ strtolower($dtid->nicetime($log->timestamp)) }}</span>
                                            </small>
                                        </td>
                                        <td class="text-center">{{ ucwords($log->env) }}</td>
                                        <td class="text-center">
                                            @if (strtolower($log->type) == 'emergency' ||
                                                    strtolower($log->type) == 'alert' ||
                                                    strtolower($log->type) == 'critical' ||
                                                    strtolower($log->type) == 'error')
                                                <span class="badge bg-danger">{{ ucwords(strtolower($log->type)) }}</span>
                                            @elseif(strtolower($log->type) == 'warning' || strtolower($log->type) == 'notice')
                                                <span
                                                    class="badge bg-warning text-dark">{{ ucwords(strtolower($log->type)) }}</span>
                                            @elseif(strtolower($log->type) == 'info')
                                                <span
                                                    class="badge bg-info text-dark">{{ ucwords(strtolower($log->type)) }}</span>
                                            @else
                                                <span
                                                    class="badge bg-secondary">{{ ucwords(strtolower($log->type)) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $log->message }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="4">Tidak ada data.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- pagination -->
                <div class="row mt-3 justify-content-between">
                    <div class="col-auto mr-auto">
                        <p>Menampilkan {{ $rs_logs->count() }} dari total {{ $rs_logs->total() }} data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $rs_logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
