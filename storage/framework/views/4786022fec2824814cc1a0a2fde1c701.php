<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Verification Code – TheSPARK</title>
</head>
<body style="margin:0;padding:0;background:#f0f4f8;font-family:'Segoe UI',Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f4f8;min-height:100vh;">
    <tr>
      <td align="center" style="padding:48px 16px;">
        <table width="540" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:20px;overflow:hidden;box-shadow:0 4px 24px rgba(30,64,175,0.10);">

          <!-- Header -->
          <tr>
            <td style="background:linear-gradient(135deg,#1e40af 0%,#2563eb 60%,#3b82f6 100%);padding:40px 40px 36px;text-align:center;">
              <p style="margin:0 0 6px;font-size:11px;color:rgba(255,255,255,0.75);letter-spacing:4px;text-transform:uppercase;font-weight:600;">The SPARK Publication</p>
              <h1 style="margin:0 0 4px;font-size:32px;font-weight:800;color:#ffffff;letter-spacing:-1px;">TheSPARK</h1>
              <p style="margin:0;font-size:12px;color:rgba(255,255,255,0.65);font-style:italic;letter-spacing:0.5px;">Truth knows no limits</p>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding:40px 44px 32px;">
              <h2 style="margin:0 0 8px;font-size:22px;font-weight:700;color:#1e293b;">Hi, <?php echo e($recipientName); ?>! 👋</h2>
              <p style="margin:0 0 28px;font-size:15px;color:#475569;line-height:1.7;">
                You're almost there! Use the 6-digit verification code below to confirm your email address and complete your <strong style="color:#1e40af;">TheSPARK</strong> account setup.
              </p>

              <!-- OTP Box -->
              <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
                <tr>
                  <td style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border:1.5px solid #bfdbfe;border-radius:14px;padding:30px 20px;text-align:center;">
                    <p style="margin:0 0 10px;font-size:11px;color:#3b82f6;letter-spacing:3px;text-transform:uppercase;font-weight:600;">Your Verification Code</p>
                    <p style="margin:0;font-size:52px;font-weight:800;letter-spacing:14px;color:#1e40af;font-family:'Courier New',monospace;"><?php echo e($otpCode); ?></p>
                  </td>
                </tr>
              </table>

              <!-- Info row -->
              <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                <tr>
                  <td width="50%" style="text-align:center;padding:12px 8px;background:#f8fafc;border-radius:10px 0 0 10px;border:1px solid #e2e8f0;">
                    <p style="margin:0;font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;">Expires in</p>
                    <p style="margin:4px 0 0;font-size:16px;font-weight:700;color:#1e293b;">10 minutes</p>
                  </td>
                  <td width="50%" style="text-align:center;padding:12px 8px;background:#f8fafc;border-radius:0 10px 10px 0;border:1px solid #e2e8f0;border-left:none;">
                    <p style="margin:0;font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;">Single use</p>
                    <p style="margin:4px 0 0;font-size:16px;font-weight:700;color:#1e293b;">One time only</p>
                  </td>
                </tr>
              </table>

              <p style="margin:0;font-size:13px;color:#94a3b8;text-align:center;line-height:1.6;">
                If you didn't create a TheSPARK account, you can safely ignore this email.<br>No action is needed.
              </p>
            </td>
          </tr>

          <!-- Divider -->
          <tr>
            <td style="padding:0 44px;">
              <div style="height:1px;background:linear-gradient(to right,transparent,#e2e8f0,transparent);"></div>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="padding:24px 44px 32px;text-align:center;">
              <p style="margin:0 0 6px;font-size:13px;font-weight:600;color:#1e40af;">TheSPARK Publication</p>
              <p style="margin:0;font-size:12px;color:#94a3b8;">
                © <?php echo e(date('Y')); ?> TheSPARK · All rights reserved
              </p>
            </td>
          </tr>

        </table>

        <!-- Bottom note -->
        <p style="margin:20px 0 0;font-size:12px;color:#94a3b8;text-align:center;">
          This is an automated message — please do not reply.
        </p>
      </td>
    </tr>
  </table>
</body>
</html>
<?php /**PATH C:\Users\emher\Desktop\Sparky\resources\views/emails/otp.blade.php ENDPATH**/ ?>