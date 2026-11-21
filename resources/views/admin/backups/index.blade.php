@extends('layouts.admin')

@section('title', 'Database & Media Backups')
@section('page_title', 'Automated Backups & Disaster Recovery')

@section('breadcrumb')
  <li class="breadcrumb-item active">Backups</li>
@endsection

@section('content')
<!-- Overview Stat Cards -->
<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box bg-info shadow-sm">
      <div class="inner">
        <h3>{{ count($files) }}</h3>
        <p>মোট ব্যাকআপ ফাইল (Total Backups)</p>
      </div>
      <div class="icon">
        <i class="fas fa-archive"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-success shadow-sm">
      <div class="inner">
        <h3>{{ $totalSizeFormatted }}</h3>
        <p>ব্যবহৃত মেমরি (Total Storage Used)</p>
      </div>
      <div class="icon">
        <i class="fas fa-hdd"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning shadow-sm">
      <div class="inner">
        <h3>রাত ০২:০০</h3>
        <p>অটো ব্যাকআপ শিডিউল (Daily at 02:00 AM)</p>
      </div>
      <div class="icon">
        <i class="fas fa-clock"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-secondary shadow-sm">
      <div class="inner">
        <h3>সক্রিয়</h3>
        <p>অটো ক্লিনআপ পলিসি (Auto Pruning Active)</p>
      </div>
      <div class="icon">
        <i class="fas fa-recycle"></i>
      </div>
    </div>
  </div>
</div>

<!-- Main Backup Card -->
<div class="row">
  <div class="col-12">
    <div class="card card-outline card-success shadow-sm">
      <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center">
        <div>
          <h3 class="card-title font-weight-bold text-dark m-0">
            <i class="fas fa-database text-success mr-2"></i> ডাটাবেজ ও মিডিয়া ব্যাকআপ ফাইল তালিকা (Backup Archives)
          </h3>
          <p class="text-muted small mb-0 mt-1">
            দৈনিক স্বয়ংক্রিয় ব্যাকআপসমূহ এখানে সংরক্ষিত হয়। আপনি যেকোনো সময় এক ক্লিকে ডাউনলোড বা ম্যানুয়াল ব্যাকআপ নিতে পারবেন।
          </p>
        </div>

        <div class="card-tools mt-2 mt-md-0 d-flex flex-wrap" style="gap: 8px;">
          <!-- Manual Backup Buttons -->
          <form action="{{ route('admin.backups.store') }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="type" value="full">
            <button type="submit" class="btn btn-success btn-sm font-weight-bold shadow-sm" onclick="return confirm('সম্পূর্ণ ডাটাবেজ এবং মিডিয়া ফাইলের ব্যাকআপ শুরু করতে চান?')">
              <i class="fas fa-cloud-download-alt mr-1"></i> ফুল ব্যাকআপ নিন (Full Backup)
            </button>
          </form>

          <form action="{{ route('admin.backups.store') }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="type" value="db">
            <button type="submit" class="btn btn-outline-primary btn-sm font-weight-bold shadow-sm" onclick="return confirm('শুধুমাত্র ডাটাবেজের ব্যাকআপ শুরু করতে চান?')">
              <i class="fas fa-database mr-1"></i> শুধু ডাটাবেজ (DB Only)
            </button>
          </form>

          <form action="{{ route('admin.backups.store') }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="type" value="files">
            <button type="submit" class="btn btn-outline-secondary btn-sm font-weight-bold shadow-sm" onclick="return confirm('শুধুমাত্র মিডিয়া ও আপলোডকৃত ফাইলের ব্যাকআপ শুরু করতে চান?')">
              <i class="fas fa-images mr-1"></i> শুধু মিডিয়া ফাইলস (Media Only)
            </button>
          </form>
        </div>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-striped align-middle" id="backupsTable">
            <thead class="thead-light">
              <tr>
                <th style="width: 50px;" class="text-center">#</th>
                <th>ফাইলের নাম (Archive File Name)</th>
                <th style="width: 140px;" class="text-center">ফাইলের আকার (Size)</th>
                <th style="width: 200px;">তৈরির তারিখ ও সময় (Created Date)</th>
                <th style="width: 140px;">বয়স (Age)</th>
                <th style="width: 170px;" class="text-center">অ্যাকশন (Actions)</th>
              </tr>
            </thead>
            <tbody>
              @forelse($files as $index => $b)
                <tr>
                  <td class="text-center font-weight-bold">{{ $index + 1 }}</td>
                  <td>
                    <i class="fas fa-file-archive text-warning mr-2" style="font-size: 1.2rem;"></i>
                    <span class="font-weight-bold text-dark">{{ $b['name'] }}</span>
                  </td>
                  <td class="text-center">
                    <span class="badge badge-info px-2 py-1 font-weight-bold">
                      {{ $b['size'] }}
                    </span>
                  </td>
                  <td>
                    <small class="text-dark font-weight-bold">
                      <i class="far fa-calendar-alt mr-1 text-muted"></i> {{ $b['date'] }}
                    </small>
                  </td>
                  <td>
                    <span class="text-muted small">
                      <i class="far fa-clock mr-1"></i> {{ $b['age'] }}
                    </span>
                  </td>
                  <td class="text-center">
                    <div class="btn-group btn-group-sm">
                      <a href="{{ route('admin.backups.download', $b['name']) }}" class="btn btn-primary" title="কম্পিউটারে ডাউনলোড করুন">
                        <i class="fas fa-download mr-1"></i> ডাউনলোড
                      </a>

                      <form action="{{ route('admin.backups.destroy', $b['name']) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই ব্যাকআপ ফাইলটি ডিলিট করতে চান?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" title="ডিলিট করুন">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center py-5 text-muted">
                    <i class="fas fa-box-open fa-3x mb-3 d-block text-secondary"></i>
                    বর্তমানে কোনো ব্যাকআপ ফাইল নেই। উপরের বাটন দিয়ে যেকোনো সময় নতুন ব্যাকআপ তৈরি করতে পারেন।
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Production Crontab Setup Guide Card -->
<div class="row">
  <div class="col-12">
    <div class="card card-outline card-info shadow-sm">
      <div class="card-header bg-white py-3">
        <h5 class="card-title font-weight-bold text-dark m-0">
          <i class="fas fa-server text-info mr-2"></i> প্রোডাকশন সার্ভার কনফিগারেশন নির্দেশিকা (Production Cron Setup)
        </h5>
      </div>
      <div class="card-body">
        <p class="text-muted small mb-2">
          লাইভ প্রোডাকশন সার্ভারে (cPanel / VPS / Ubuntu / Cloud Server) প্রতিদিন স্বয়ংক্রিয় ব্যাকআপ ও অটো-ক্লিনআপ সক্রিয় রাখার জন্য সার্ভারের Crontab-এ শুধুমাত্র নিচের একটি লাইন যুক্ত করে দিন:
        </p>

        <div class="bg-dark p-3 rounded mb-3 font-weight-bold" style="font-family: monospace; font-size: 0.95rem;">
          * * * * * cd {{ base_path() }} && php artisan schedule:run >> /dev/null 2>&1
        </div>

        <div class="row">
          <div class="col-md-4">
            <div class="border rounded p-3 h-100 bg-light">
              <h6 class="font-weight-bold text-dark"><i class="fas fa-calendar-check text-success mr-1"></i> ১. দৈনিক ব্যাকআপ</h6>
              <p class="small text-muted mb-0">প্রতিদিন রাত ০২:০০ টায় ডাটাবেজ এবং মিডিয়া ফাইলের ব্যাকআপ তৈরি হয়ে আর্কাইভে জমা হবে।</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="border rounded p-3 h-100 bg-light">
              <h6 class="font-weight-bold text-dark"><i class="fas fa-broom text-warning mr-1"></i> ২. অটো ক্লিনআপ</h6>
              <p class="small text-muted mb-0">প্রতিদিন রাত ০২:৩০ টায় রিটেনশন পলিসি অনুযায়ী পুরোনো অপ্রয়োজনীয় ব্যাকআপগুলো মুছে ডিস্ক ফাঁকা রাখবে।</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="border rounded p-3 h-100 bg-light">
              <h6 class="font-weight-bold text-dark"><i class="fas fa-heartbeat text-danger mr-1"></i> ৩. ব্যাকআপ হেলথ মনিটর</h6>
              <p class="small text-muted mb-0">প্রতিদিন রাত ০৩:০০ টায় ব্যাকআপের স্বাস্থ্য পরীক্ষা করে কোনো অসংগতি থাকলে ইমেইল অ্যালার্ট পাঠাবে।</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
