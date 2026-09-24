<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome to TheSPARK</title>
</head>
<body style="margin:0;padding:0;background:#f0f4f8;font-family:'Segoe UI',Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f4f8;">
    <tr>
      <td align="center" style="padding:48px 16px;">
        <table width="540" cellpadding="0" cellspacing="0" style="max-width:540px;width:100%;background:#ffffff;border-radius:20px;overflow:hidden;box-shadow:0 4px 24px rgba(30,64,175,0.10);">
          <tr>
            <td style="background:linear-gradient(135deg,#1e40af 0%,#2563eb 60%,#3b82f6 100%);padding:36px 40px;text-align:center;">
              <h1 style="margin:0 0 4px;font-size:30px;font-weight:800;color:#ffffff;letter-spacing:-1px;">TheSPARK</h1>
              <p style="margin:0;font-size:12px;color:rgba(255,255,255,0.7);font-style:italic;">Truth knows no limits</p>
            </td>
          </tr>
          <tr>
            <td style="padding:36px 44px 28px;">
              <h2 style="margin:0 0 10px;font-size:22px;font-weight:700;color:#1e293b;">You're subscribed 🎉</h2>
              <p style="margin:0 0 24px;font-size:15px;color:#475569;line-height:1.7;">
                Thank you for joining the <strong style="color:#1e40af;">TheSPARK</strong> newsletter. Every week we'll send you the stories our newsroom has published, so you never miss what's happening on campus.
              </p>
              <a href="{{ $siteUrl }}" style="display:inline-block;background:#1d6bf3;color:#ffffff;text-decoration:none;font-weight:700;font-size:14px;padding:12px 26px;border-radius:999px;">Read the latest stories</a>
            </td>
          </tr>
          <tr>
            <td style="padding:20px 44px 30px;text-align:center;border-top:1px solid #e2e8f0;">
              <p style="margin:0;font-size:12px;color:#94a3b8;line-height:1.6;">
                Didn't sign up? <a href="{{ $unsubscribeUrl }}" style="color:#64748b;">Unsubscribe</a> and we won't email you again.<br>
                © {{ date('Y') }} TheSPARK · All rights reserved
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
