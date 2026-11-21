@extends('layouts.admin')

@section('title', 'Section Visibility Controls')
@section('page_title', 'ল্যান্ডিং পেজ সেকশন কন্ট্রোল (Section On/Off)')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">Section Controls</li>
@endsection

@section('content')
<div class="row mb-3">
  <div class="col-12">
    <div class="callout callout-success bg-white shadow-sm d-flex flex-wrap align-items-center justify-content-between">
      <div>
        <h5 class="mb-1 text-success font-weight-bold">
          <i class="fas fa-sliders-h mr-2"></i> ওয়েবসাইট সেকশন সম্পূর্ণ নিয়ন্ত্রণ
        </h5>
        <p class="mb-0 text-muted">
          যেকোনো সেকশন ওয়েবসাইট থেকে এক ক্লিকে অন/অফ করতে পারবেন। যে সেকশনটি বন্ধ (Disable) করবেন, হোমপেজে সেটি প্রদর্শিত হবে না।
        </p>
      </div>
      <div class="mt-2 mt-md-0 d-flex gap-2">
        <button type="button" class="btn btn-outline-success btn-sm font-weight-bold mr-2" id="btnEnableAll">
          <i class="fas fa-check-double mr-1"></i> সবগুলো চালু করুন (Enable All)
        </button>
        <button type="button" class="btn btn-outline-danger btn-sm font-weight-bold mr-2" id="btnDisableAll">
          <i class="fas fa-ban mr-1"></i> সবগুলো বন্ধ করুন (Disable All)
        </button>
        <a href="{{ route('home') }}" target="_blank" class="btn btn-primary btn-sm font-weight-bold">
          <i class="fas fa-external-link-alt mr-1"></i> ওয়েবসাইট দেখুন
        </a>
      </div>
    </div>
  </div>
</div>

@php
  $activeCount = 0;
  foreach($sections as $sec) {
    if (($settings[$sec['key']] ?? '1') == '1') {
      $activeCount++;
    }
  }
  $hiddenCount = count($sections) - $activeCount;
@endphp

<!-- Status Overview Cards -->
<div class="row mb-3">
  <div class="col-md-4 col-sm-6 col-12">
    <div class="info-box shadow-sm">
      <span class="info-box-icon bg-info"><i class="fas fa-cubes"></i></span>
      <div class="info-box-content">
        <span class="info-box-text font-weight-bold">মোট ল্যান্ডিং পেজ সেকশন</span>
        <span class="info-box-number text-dark font-weight-bold fs-4">{{ count($sections) }} টি</span>
      </div>
    </div>
  </div>

  <div class="col-md-4 col-sm-6 col-12">
    <div class="info-box shadow-sm">
      <span class="info-box-icon bg-success"><i class="fas fa-eye"></i></span>
      <div class="info-box-content">
        <span class="info-box-text font-weight-bold text-success">বর্তমানে ওয়েবসাইটে দৃশ্যমান</span>
        <span class="info-box-number text-success font-weight-bold fs-4" id="statActiveCount">{{ $activeCount }} টি</span>
      </div>
    </div>
  </div>

  <div class="col-md-4 col-sm-6 col-12">
    <div class="info-box shadow-sm">
      <span class="info-box-icon bg-secondary"><i class="fas fa-eye-slash"></i></span>
      <div class="info-box-content">
        <span class="info-box-text font-weight-bold text-muted">লুকানো / নিষ্ক্রিয় সেকশন</span>
        <span class="info-box-number text-danger font-weight-bold fs-4" id="statHiddenCount">{{ $hiddenCount }} টি</span>
      </div>
    </div>
  </div>
</div>

<form action="{{ route('admin.sections.update') }}" method="POST" id="sectionControlsForm">
  @csrf
  @method('PUT')

  <div class="card card-outline card-success shadow-sm">
    <div class="card-header bg-white py-3">
      <h3 class="card-title font-weight-bold text-dark mb-0">
        <i class="fas fa-list-check mr-2 text-success"></i> প্রতিটি সেকশনের অন/অফ স্ট্যাটাস নির্ধারণ করুন
      </h3>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover table-striped align-middle mb-0">
          <thead class="bg-light">
            <tr>
              <th style="width: 50px;" class="text-center">#</th>
              <th style="width: 320px;">সেকশনের নাম ও ধরন</th>
              <th>সেকশনের বিবরণ ও অবস্থান</th>
              <th style="width: 150px;" class="text-center">বর্তমান অবস্থা</th>
              <th style="width: 220px;" class="text-center">প্রদর্শন নিয়ন্ত্রণ (Visibility)</th>
              <th style="width: 180px;" class="text-center">কনটেন্ট অ্যাকশন</th>
            </tr>
          </thead>
          <tbody>
            @foreach($sections as $index => $sec)
              @php
                $isEnabled = ($settings[$sec['key']] ?? '1') == '1';
              @endphp
              <tr class="{{ $isEnabled ? '' : 'table-light text-muted' }}" id="row_{{ $sec['key'] }}">
                <td class="text-center font-weight-bold text-muted">{{ $index + 1 }}</td>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="rounded p-2 mr-3 bg-light text-center border" style="width: 44px; height: 44px;">
                      <i class="{{ $sec['icon'] }} fa-lg"></i>
                    </div>
                    <div>
                      <div class="font-weight-bold text-dark">{{ $sec['title_bn'] }}</div>
                      <small class="text-muted">{{ $sec['title_en'] }}</small>
                    </div>
                  </div>
                </td>
                <td>
                  <p class="mb-1 text-secondary small">{{ $sec['description'] }}</p>
                  <span class="badge badge-light border">{{ $sec['badge'] }}</span>
                </td>
                <td class="text-center">
                  <span id="badge_{{ $sec['key'] }}" class="badge {{ $isEnabled ? 'badge-success' : 'badge-danger' }} px-3 py-2 font-weight-bold">
                    {{ $isEnabled ? '🟢 দৃশ্যমান (Active)' : '🔴 লুকানো (Hidden)' }}
                  </span>
                </td>
                <td>
                  <select name="{{ $sec['key'] }}" 
                          class="form-control form-control-sm font-weight-bold section-select {{ $isEnabled ? 'text-success border-success' : 'text-danger border-danger' }}" 
                          data-key="{{ $sec['key'] }}">
                    <option value="1" {{ $isEnabled ? 'selected' : '' }}>✅ দৃশ্যমান (Show Section)</option>
                    <option value="0" {{ !$isEnabled ? 'selected' : '' }}>🚫 লুকিয়ে রাখুন (Hide Section)</option>
                  </select>
                </td>
                <td class="text-center">
                  <a href="{{ $sec['edit_url'] }}" class="btn btn-xs btn-outline-info font-weight-bold" title="কনটেন্ট সম্পাদনা করুন">
                    <i class="fas fa-pen mr-1"></i> {{ $sec['edit_label'] }}
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div class="card-footer bg-white text-right py-3 border-top">
      <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mr-2">
        <i class="fas fa-times mr-1"></i> বাতিল
      </a>
      <button type="submit" class="btn btn-success font-weight-bold px-4 shadow-sm">
        <i class="fas fa-save mr-1"></i> পরিবর্তনসমূহ সংরক্ষণ করুন (Save Settings)
      </button>
    </div>
  </div>
</form>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const selects = document.querySelectorAll('.section-select');
    const statActive = document.getElementById('statActiveCount');
    const statHidden = document.getElementById('statHiddenCount');

    function updateSectionRow(select) {
      const key = select.getAttribute('data-key');
      const badge = document.getElementById('badge_' + key);
      const row = document.getElementById('row_' + key);

      if (select.value === '1') {
        select.classList.remove('text-danger', 'border-danger');
        select.classList.add('text-success', 'border-success');
        badge.className = 'badge badge-success px-3 py-2 font-weight-bold';
        badge.textContent = '🟢 দৃশ্যমান (Active)';
        row.classList.remove('table-light', 'text-muted');
      } else {
        select.classList.remove('text-success', 'border-success');
        select.classList.add('text-danger', 'border-danger');
        badge.className = 'badge badge-danger px-3 py-2 font-weight-bold';
        badge.textContent = '🔴 লুকানো (Hidden)';
        row.classList.add('table-light', 'text-muted');
      }
      refreshStats();
    }

    function refreshStats() {
      let active = 0;
      let hidden = 0;
      selects.forEach(sel => {
        if (sel.value === '1') active++;
        else hidden++;
      });
      if (statActive) statActive.textContent = active + ' টি';
      if (statHidden) statHidden.textContent = hidden + ' টি';
    }

    selects.forEach(select => {
      select.addEventListener('change', function () {
        updateSectionRow(this);
      });
    });

    // Enable All Button
    const btnEnableAll = document.getElementById('btnEnableAll');
    if (btnEnableAll) {
      btnEnableAll.addEventListener('click', function () {
        selects.forEach(select => {
          select.value = '1';
          updateSectionRow(select);
        });
      });
    }

    // Disable All Button
    const btnDisableAll = document.getElementById('btnDisableAll');
    if (btnDisableAll) {
      btnDisableAll.addEventListener('click', function () {
        selects.forEach(select => {
          select.value = '0';
          updateSectionRow(select);
        });
      });
    }
  });
</script>
@endpush
