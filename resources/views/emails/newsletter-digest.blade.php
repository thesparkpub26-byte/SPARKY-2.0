<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>The latest from TheSPARK</title>
</head>
<body style="margin:0;padding:0;background:#f0f4f8;font-family:'Segoe UI',Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f4f8;">
    <tr>
      <td align="center" style="padding:48px 16px;">
        <table width="580" cellpadding="0" cellspacing="0" style="max-width:580px;width:100%;background:#ffffff;border-radius:20px;overflow:hidden;box-shadow:0 4px 24px rgba(30,64,175,0.10);">
          <tr>
            <td style="background:linear-gradient(135deg,#1e40af 0%,#2563eb 60%,#3b82f6 100%);padding:32px 40px;text-align:center;">
              <h1 style="margin:0 0 4px;font-size:28px;font-weight:800;color:#ffffff;letter-spacing:-1px;">TheSPARK</h1>
              <p style="margin:0;font-size:12px;color:rgba(255,255,255,0.7);">This week's stories</p>
            </td>
          </tr>

          @foreach ($stories as $story)
          <tr>
            <td style="padding:24px 40px 4px;">
              @if ($story['image'])
                <a href="{{ $story['url'] }}"><img src="{{ $story['image'] }}" alt="" width="500" style="display:block;width:100%;max-width:500px;height:auto;border-radius:14px;margin-bottom:14px;"></a>
              @endif
              @if ($story['category'])
                <p style="margin:0 0 6px;font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#1d6bf3;">{{ $story['category'] }}</p>
              @endif
              <h2 style="margin:0 0 8px;font-size:19px;line-height:1.3;color:#0f172a;"><a href="{{ $story['url'] }}" style="color:#0f172a;text-decoration:none;">{{ $story['title'] }}</a></h2>
              <p style="margin:0 0 10px;font-size:14px;color:#475569;line-height:1.6;">{{ $story['excerpt'] }}</p>
              <a href="{{ $story['url'] }}" style="font-size:13px;font-weight:700;color:#1d6bf3;text-decoration:none;">Read more →</a>
            </td>
          </tr>
          <tr><td style="padding:16px 40px 0;"><div style="height:1px;background:#e2e8f0;"></div></td></tr>
          @endforeach

          <tr>
            <td style="padding:24px 40px 30px;text-align:center;">
              <a href="{{ $siteUrl }}" style="display:inline-block;background:#1d6bf3;color:#ffffff;text-decoration:none;font-weight:700;font-size:14px;padding:12px 26px;border-radius:999px;">Visit TheSPARK</a>
              <p style="margin:20px 0 0;font-size:12px;color:#94a3b8;line-height:1.6;">
                You're receiving this because you subscribed to the TheSPARK newsletter.<br>
                <a href="{{ $unsubscribeUrl }}" style="color:#64748b;">Unsubscribe</a>
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
