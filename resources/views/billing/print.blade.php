<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bill Receipt</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            padding: 30px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #111;
        }

        .receipt {
            width: 320px;
            background: #fff;
            padding: 20px 18px;
            border: 1px dashed #bbb;
            line-height: 1.6;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 8px;
        }

        .receipt-header .shop-name {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .receipt-header .shop-sub {
            font-size: 11px;
            color: #555;
        }

        .dashed-divider {
            border: none;
            border-top: 1px dashed #999;
            margin: 8px 0;
        }

        .bill-label {
            text-align: center;
            padding: 4px 0;
            font-size: 11px;
            letter-spacing: 1px;
            border-top: 1px dashed #999;
            border-bottom: 1px dashed #999;
            margin: 10px 0;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }

        .customer-info {
            font-size: 11px;
            border-top: 1px dashed #999;
            padding-top: 6px;
            margin-top: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        thead th {
            text-align: left;
            padding-bottom: 4px;
        }

        thead th:nth-child(3),
        thead th:nth-child(4),
        thead th:nth-child(5),
        tbody td:nth-child(3),
        tbody td:nth-child(4),
        tbody td:nth-child(5) {
            text-align: right;
        }

        tbody td {
            padding: 2px 0;
            vertical-align: top;
        }

        tbody td:nth-child(2) {
            padding-right: 4px;
            max-width: 110px;
            word-break: break-word;
        }

        .totals-table td { padding: 2px 0; }
        .totals-table td:last-child { text-align: right; }

        .net-payable {
            border-top: 2px solid #111;
            border-bottom: 2px solid #111;
            margin: 6px 0;
            padding: 4px 0;
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            font-weight: 700;
        }

        .cash-table td { padding: 2px 0; font-size: 11px; }
        .cash-table td:last-child { text-align: right; }

        .receipt-footer {
            text-align: center;
            font-size: 11px;
            color: #444;
            margin-top: 10px;
        }

        .receipt-footer .powered {
            font-size: 10px;
            color: #888;
            margin-top: 6px;
        }

        .print-btn-wrap {
            text-align: center;
            margin-top: 14px;
        }

        .print-btn {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            padding: 6px 20px;
            border: 1px solid #333;
            background: #fff;
            cursor: pointer;
            letter-spacing: 1px;
        }

        @media print {
            body { background: #fff; padding: 0; }
            .print-btn-wrap { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="receipt">

    <div class="receipt-header">
        <div class="shop-name">FINAL POS</div>
        <div class="shop-sub">Retail Point of Sale</div>
        <div class="shop-sub">Ph: 87XXXXX345</div>
    </div>

    <div class="bill-label">CASH BILL / RECEIPT</div>

    <div class="meta-row">
        <span>Bill No : <strong>{{ str_pad($billId ?? '001', 4, '0', STR_PAD_LEFT) }}</strong></span>
        <span>Date: <strong>{{ now()->format('d/m/Y') }}</strong></span>
    </div>
    <div style="font-size:11px;">Time : {{ now()->setTimezone('Asia/Kolkata')->format('h:i A') }}</div>

    <div class="customer-info">
        <div>Customer : <strong>{{ $customerName }}</strong></div>
        <div>Phone&nbsp;&nbsp;&nbsp; : <strong>{{ $customerPhone }}</strong></div>
    </div>

    <hr class="dashed-divider">

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Item</th> 
                <th>Rate</th>
                <th>Amt</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item['product_name'] }}</td>
                    {{-- <td>{{ $item['quantity'] }}</td> --}}
                    <td>{{ number_format($item['price'], 2) }}</td>
                    <td>{{ number_format($item['line_total'], 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:8px 0;color:#888">No items</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <hr class="dashed-divider">

    <table class="totals-table">
        <tr><td>Subtotal</td><td>Rs. {{ number_format($totalAmount, 2) }}</td></tr>
        <tr><td>Discount</td><td>Rs. 0.00</td></tr>
        <tr><td>CGST (5%)</td><td>Rs. {{ number_format($totalAmount * 0.05, 2) }}</td></tr>
    </table>

    <div class="net-payable">
        <span>NET PAYABLE</span>
        <span>Rs. {{ number_format($totalAmount * 1.05, 2) }}</span>
    </div>

    <table class="cash-table">
        <tr><td>Cash Tendered</td><td>Rs. ___________</td></tr>
        <tr><td>Balance</td><td>Rs. ___________</td></tr>
    </table>

    <hr class="dashed-divider">

    <div class="receipt-footer">
        <div>Items Sold : <strong>{{ collect($items)->sum('quantity') }}</strong></div>
        <div style="margin-top:6px">**** THANK YOU ****</div>
        <div>Visit Again!</div>
        <div class="powered">Powered by Final POS</div>
    </div>

    <div class="print-btn-wrap">
        <button class="print-btn" onclick="window.print()">PRINT BILL</button>
    </div>

</div>
</body>
</html>