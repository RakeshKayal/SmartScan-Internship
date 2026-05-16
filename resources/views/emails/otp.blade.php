```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
</head>

<body style="margin:0; padding:0; background-color:#050816; font-family:Arial, Helvetica, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#050816; padding:40px 0;">
<tr>
<td align="center">

<!-- MAIN CARD -->
<table width="560" cellpadding="0" cellspacing="0"
       style="
            background:#0b1120;
            border:1px solid #1e293b;
            border-radius:20px;
            overflow:hidden;
            box-shadow:
                0 0 20px rgba(0,255,255,0.08),
                0 0 50px rgba(0,255,255,0.05);
       ">

    <!-- TOP COMPANY BAR -->
    <tr>
        <td style="
            background:linear-gradient(90deg,#020617,#0f172a);
            padding:18px 30px;
            border-bottom:1px solid #1e293b;
        ">

            <table width="100%">
                <tr>

                    <!-- LEFT -->
                    <td align="left">

                        <div style="
                            color:#22d3ee;
                            font-size:30px;
                            font-weight:900;
                            letter-spacing:3px;
                            text-shadow:
                                0 0 5px #22d3ee,
                                0 0 12px #22d3ee;
                        ">
                            LUXES
                        </div>

                        <div style="
                            color:#94a3b8;
                            font-size:13px;
                            margin-top:6px;
                            line-height:20px;
                        ">
                            Imagine Tech Park, Kochi, Kerala
                        </div>

                    </td>

                    <!-- RIGHT -->
                    <td align="right">

                        <div style="
                            color:#67e8f9;
                            font-size:13px;
                            font-weight:bold;
                            letter-spacing:1px;
                        ">
                            STAFF SECURITY PORTAL
                        </div>

                        <div style="
                            color:#64748b;
                            font-size:12px;
                            margin-top:5px;
                        ">
                            Secure Authentication System
                        </div>

                    </td>

                </tr>
            </table>

        </td>
    </tr>

    <!-- HEADER -->
    <tr>
        <td align="center"
            style="
                padding:38px 30px 25px;
                background:linear-gradient(180deg,#0f172a,#0b1120);
            ">

            <h1 style="
                margin:0;
                color:#67e8f9;
                font-size:30px;
                font-weight:800;
                letter-spacing:1px;
            ">
                OTP VERIFICATION
            </h1>

            <p style="
                margin-top:14px;
                color:#94a3b8;
                font-size:15px;
                line-height:24px;
                max-width:420px;
            ">
                A verification request was initiated for a new staff account.
                Use the secure one-time password below to continue.
            </p>

        </td>
    </tr>

    <!-- BODY -->
    <tr>
        <td style="padding:10px 35px 40px;">

            <!-- OTP BOX -->
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center">

                        <div style="
                            background:#020617;
                            border:2px solid #22d3ee;
                            border-radius:18px;
                            padding:28px;
                            margin:10px 0 30px;
                            box-shadow:
                                0 0 8px rgba(34,211,238,0.6),
                                0 0 25px rgba(34,211,238,0.25);
                        ">

                            <div style="
                                color:#64748b;
                                font-size:13px;
                                margin-bottom:14px;
                                letter-spacing:1px;
                            ">
                                YOUR SECURE OTP
                            </div>

                            <span style="
                                font-size:48px;
                                font-weight:900;
                                letter-spacing:14px;
                                color:#22d3ee;
                                text-shadow:
                                    0 0 5px #22d3ee,
                                    0 0 12px #22d3ee,
                                    0 0 22px #22d3ee;
                            ">
                                {{ $otp }}
                            </span>

                        </div>

                    </td>
                </tr>
            </table>

            <!-- INFO BOX -->
            <div style="
                background:#0f172a;
                border:1px solid #1e293b;
                border-radius:14px;
                padding:20px;
                margin-bottom:25px;
            ">

                <table width="100%">
                    <tr>
                        <td style="color:#94a3b8; font-size:14px; padding:6px 0;">
                            Verification Type
                        </td>

                        <td align="right" style="color:#e2e8f0; font-size:14px; font-weight:bold;">
                            Staff Registration
                        </td>
                    </tr>

                    <tr>
                        <td style="color:#94a3b8; font-size:14px; padding:6px 0;">
                            Expiry Time
                        </td>

                        <td align="right" style="color:#facc15; font-size:14px; font-weight:bold;">
                            40 Seconds
                        </td>
                    </tr>

                    <tr>
                        <td style="color:#94a3b8; font-size:14px; padding:6px 0;">
                            Security Level
                        </td>

                        <td align="right" style="color:#4ade80; font-size:14px; font-weight:bold;">
                            High
                        </td>
                    </tr>
                </table>

            </div>

            <!-- WARNING -->
            <div style="
                background:rgba(239,68,68,0.08);
                border:1px solid rgba(239,68,68,0.25);
                border-radius:12px;
                padding:16px;
            ">

                <p style="
                    margin:0;
                    color:#fca5a5;
                    font-size:13px;
                    line-height:22px;
                ">
                    Never share this OTP with anyone. LUXES staff will never ask
                    for your verification code through phone calls or messages.
                </p>

            </div>

        </td>
    </tr>

    <!-- FOOTER -->
    <tr>
        <td align="center"
            style="
                padding:24px;
                border-top:1px solid #1e293b;
                background:#020617;
            ">

            <p style="
                margin:0;
                color:#64748b;
                font-size:12px;
                line-height:22px;
            ">
                © 2026 LUXES Technologies Pvt. Ltd.<br>
                Imagine Tech Park, Kochi, Kerala
            </p>

        </td>
    </tr>

</table>

</td>
</tr>
</table>

</body>
</html>
```
