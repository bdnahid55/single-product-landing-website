@php
  $siteFavVal = !empty($settings['site_favicon']) 
    ? $settings['site_favicon'] 
    : (\Illuminate\Support\Facades\Schema::hasTable('settings') ? \App\Models\Setting::get('site_favicon') : null);
  $customFavicon = !empty($siteFavVal)
    ? (str_starts_with($siteFavVal, 'http') ? $siteFavVal : asset($siteFavVal))
    : null;
  $favUrl = $customFavicon ?? asset('assets/images/favicon.png');
  $favVer = $customFavicon ? substr(md5($customFavicon), 0, 8) : '1';
@endphp
<link rel="icon" type="image/png" sizes="32x32" href="{{ $favUrl }}?v={{ $favVer }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ $favUrl }}?v={{ $favVer }}">
<link rel="shortcut icon" href="{{ $favUrl }}?v={{ $favVer }}">
<link rel="apple-touch-icon" href="{{ $favUrl }}?v={{ $favVer }}">
@if(!$customFavicon)
  <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/favicon.svg') }}">
@endif
