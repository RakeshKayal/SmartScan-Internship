<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Invoice {{ $invoice['number'] }}</title>
</head>
<body style="margin:0;padding:0;background-color:#05060a;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#e5e7eb;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#05060a;padding:32px 12px;">
  <tr>
    <td align="center">

      <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0"
             style="max-width:600px;width:100%;background:#0b0d14;border:1px solid #1f2937;border-radius:14px;overflow:hidden;box-shadow:0 0 40px rgba(0,255,231,0.08), 0 0 80px rgba(168,85,247,0.08);">

        {{-- Header --}}
        <tr>
          <td style="background:linear-gradient(135deg,#0f0c29 0%,#1a0b2e 50%,#0b0d14 100%);padding:32px;border-bottom:1px solid #1f2937;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td>
                  <div style="font-size:22px;font-weight:700;letter-spacing:.5px;color:#00ffe7;text-shadow:0 0 12px rgba(0,255,231,0.6);">
                    {{ $company['name'] }}
                  </div>
                  @if(!empty($company['address']))
                    <div style="font-size:13px;color:#94a3b8;margin-top:6px;">{{ $company['address'] }}</div>
                  @endif
                </td>
                <td align="right" style="vertical-align:top;">
                  <div style="font-size:11px;text-transform:uppercase;letter-spacing:2px;color:#a855f7;">Invoice</div>
                  <div style="font-size:18px;font-weight:700;margin-top:4px;color:#f0abfc;text-shadow:0 0 10px rgba(168,85,247,0.5);">
                    #{{ $invoice['number'] }}
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- Greeting --}}
        <tr>
          <td style="padding:28px 32px 8px 32px;">
            <div style="font-size:16px;font-weight:600;color:#ffffff;">Hello {{ $customer['name'] }},</div>
            <p style="font-size:14px;line-height:1.6;color:#94a3b8;margin:8px 0 0 0;">
              Thank you for your purchase. Below is a summary of your order.
            </p>
          </td>
        </tr>

        {{-- Meta box --}}
        <tr>
          <td style="padding:16px 32px 0 32px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="background:#10131c;border:1px solid #1f2937;border-radius:10px;">
              <tr>
                <td style="padding:14px 16px;font-size:13px;color:#94a3b8;">
                  <span style="color:#00ffe7;font-weight:600;">Purchase Time:</span> {{ now()->format('d M Y, h:i A') }}
                </td>
                <td align="right" style="padding:14px 16px;font-size:13px;color:#94a3b8;">
                  <span style="color:#00ffe7;font-weight:600;">Invoice #:</span> {{ $invoice['number'] }}
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- Items table --}}
        <tr>
          <td style="padding:24px 32px 8px 32px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:separate;border-spacing:0;font-size:13px;border:1px solid #1f2937;border-radius:10px;overflow:hidden;">
              <thead>
                <tr style="background:linear-gradient(90deg,#a855f7 0%,#00ffe7 100%);color:#0b0d14;">
                  <th align="left"   style="padding:12px;font-weight:700;letter-spacing:.5px;">ITEM</th>
                  <th align="center" style="padding:12px 8px;font-weight:700;letter-spacing:.5px;">QTY</th>
                  <th align="right"  style="padding:12px 8px;font-weight:700;letter-spacing:.5px;">PRICE</th>
                  <th align="right"  style="padding:12px 8px;font-weight:700;letter-spacing:.5px;">DISC.</th>
                  <th align="right"  style="padding:12px 8px;font-weight:700;letter-spacing:.5px;">CGST 5%</th>
                  <th align="right"  style="padding:12px;font-weight:700;letter-spacing:.5px;">TOTAL</th>
                </tr>
              </thead>
              <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($items as $item)
                  @php
                    $lineTotal = $item['quantity'] * $item['unit_price'];
                    $cgst      = $lineTotal * 0.05;
                    $rowTotal  = $lineTotal + $cgst;
                    $grandTotal += $rowTotal;
                  @endphp
                  <tr style="background:{{ $loop->even ? '#0d1018' : '#10131c' }};">
                    <td style="padding:12px;color:#e5e7eb;border-bottom:1px solid #1f2937;">{{ $item['name'] }}</td>
                    <td align="center" style="padding:12px 8px;color:#94a3b8;border-bottom:1px solid #1f2937;">{{ $item['quantity'] }}</td>
                    <td align="right"  style="padding:12px 8px;color:#94a3b8;border-bottom:1px solid #1f2937;">₹{{ number_format($item['unit_price'], 2) }}</td>
                    <td align="right"  style="padding:12px 8px;color:#94a3b8;border-bottom:1px solid #1f2937;">₹{{ number_format(0, 2) }}</td>
                    <td align="right"  style="padding:12px 8px;color:#94a3b8;border-bottom:1px solid #1f2937;">₹{{ number_format($cgst, 2) }}</td>
                    <td align="right"  style="padding:12px;color:#00ffe7;font-weight:700;border-bottom:1px solid #1f2937;">₹{{ number_format($rowTotal, 2) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </td>
        </tr>

        {{-- Grand total --}}
        <tr>
          <td style="padding:8px 32px 28px 32px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td></td>
                <td align="right" style="width:260px;">
                  <table cellpadding="0" cellspacing="0" border="0"
                         style="background:linear-gradient(135deg,rgba(168,85,247,0.15),rgba(0,255,231,0.15));border:1px solid #a855f7;border-radius:10px;box-shadow:0 0 20px rgba(168,85,247,0.35);">
                    <tr>
                      <td style="padding:14px 18px;font-size:13px;color:#f0abfc;font-weight:600;letter-spacing:1px;">GRAND TOTAL</td>
                      <td align="right" style="padding:14px 18px;font-size:18px;color:#00ffe7;font-weight:700;text-shadow:0 0 10px rgba(0,255,231,0.6);">
                        ₹{{ number_format($grandTotal, 2) }}
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- Footer --}}
        <tr>
          <td style="background:#08090f;border-top:1px solid #1f2937;padding:24px 32px;text-align:center;">
            <p style="margin:0;font-size:13px;color:#94a3b8;line-height:1.6;">
              We appreciate your business.<br>
              Questions? Just reply to this email — we're here to help.
            </p>
            <p style="margin:14px 0 0 0;font-size:11px;color:#475569;letter-spacing:1px;">
              © {{ date('Y') }} <span style="color:#00ffe7;">{{ $company['legal_name'] }}</span>. All rights reserved.
            </p>
          </td>
        </tr>

      </table>

    </td>
  </tr>
</table>

</body>
</html>
