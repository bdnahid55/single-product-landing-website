<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>অ্যাডমিন লগইন ওটিপি কোড</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f6f8fb;
      margin: 0;
      padding: 30px 15px;
      color: #333333;
    }
    .email-container {
      max-width: 520px;
      margin: 0 auto;
      background: #ffffff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 18px rgba(0,0,0,0.06);
      border: 1px solid #e9ecef;
    }
    .header {
      background: linear-gradient(135deg, #1e7e34, #28a745);
      color: #ffffff;
      text-align: center;
      padding: 28px 20px;
    }
    .header h1 {
      margin: 0;
      font-size: 22px;
      font-weight: 700;
    }
    .content {
      padding: 30px 25px;
      line-height: 1.6;
    }
    .greeting {
      font-size: 16px;
      font-weight: 600;
      margin-bottom: 12px;
    }
    .otp-box {
      background: #f8f9fa;
      border: 2px dashed #28a745;
      border-radius: 10px;
      text-align: center;
      padding: 20px;
      margin: 25px 0;
    }
    .otp-code {
      font-size: 34px;
      font-weight: 800;
      letter-spacing: 8px;
      color: #1e7e34;
      font-family: monospace;
      margin: 5px 0;
    }
    .otp-meta {
      font-size: 13px;
      color: #6c757d;
      margin-top: 8px;
    }
    .warning {
      background: #fff3cd;
      border-left: 4px solid #ffc107;
      padding: 12px 16px;
      font-size: 13px;
      color: #856404;
      border-radius: 4px;
      margin: 20px 0;
    }
    .footer {
      background: #f8f9fa;
      padding: 18px 20px;
      text-align: center;
      font-size: 12px;
      color: #888888;
      border-top: 1px solid #eeeeee;
    }
  </style>
</head>
<body>
  @php
    $siteTitle = \App\Models\Setting::get('site_title', 'খাঁটি সুন্দরবনের মধু');
  @endphp
  <div class="email-container">
    <div class="header">
      <h1> {{ $siteTitle }} Admin Security</h1>
      <p style="margin: 6px 0 0 0; font-size: 14px; opacity: 0.9;">দ্বিমুখী প্রমাণীকরণ (Two-Factor Authentication)</p>
    </div>

    <div class="content">
      <div class="greeting">আসসালামু আলাইকুম, {{ $user->name }}!</div>
      <p style="margin: 0 0 15px 0; font-size: 14px; color: #555555;">
        আপনার অ্যাডমিন ড্যাশবোর্ডে লগইন করার জন্য একটি ওটিপি কোড অনুরোধ করা হয়েছে। লগইন প্রক্রিয়া সম্পন্ন করতে নিচের ওটিপি কোডটি ব্যবহার করুন:
      </p>

      <div class="otp-box">
        <div style="font-size: 12px; text-transform: uppercase; color: #6c757d; font-weight: 600;">আপনার লগইন ওটিপি কোড</div>
        <div class="otp-code">{{ $otp }}</div>
        <div class="otp-meta">কোডটির মেয়াদ আগামী ১০ মিনিট পর্যন্ত কার্যকর থাকবে।</div>
      </div>

      <div class="warning">
        <strong>⚠️ সতর্কতা:</strong> এই ওটিপি কোডটি কারো সাথে শেয়ার করবেন না। আপনি যদি লগইনের চেষ্টা না করে থাকেন, তবে অবিলম্বে আপনার পাসওয়ার্ড পরিবর্তন করুন।
      </div>

      <p style="font-size: 12px; color: #777777; margin-bottom: 0;">
        অনুরোধের সময়: {{ now()->format('d M, Y h:i A') }} (IP: {{ request()->ip() }})
      </p>
    </div>

    <div class="footer">
      &copy; {{ date('Y') }} {{ \App\Models\Setting::get('footer_copyright_text') ?: $siteTitle }}। সর্বস্বত্ব সংরক্ষিত।<br>
      এটি একটি স্বয়ংক্রিয় সিকিউরিটি ইমেইল, এতে রিপ্লাই দেওয়ার প্রয়োজন নেই।
    </div>
  </div>
</body>
</html>
