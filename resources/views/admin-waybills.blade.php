<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Waybills | SOLÉA FASHION CO. Admin</title>
    <style>
        :root { color-scheme: light; font-family: Arial, sans-serif; }
        * { box-sizing: border-box; }
        body { margin: 0; background: #f3f3f3; color: #111; }
        main { max-width: 980px; margin: 0 auto; padding: 24px; }
        .toolbar { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 24px; }
        .toolbar h1 { margin: 0; font-size: 24px; }
        button { border: 0; background: #d7ff00; color: #111; padding: 12px 18px; font-weight: 700; cursor: pointer; }
        .waybill { background: #fff; border: 1px solid #bbb; padding: 26px; margin-bottom: 24px; break-inside: avoid; page-break-after: always; }
        .waybill:last-child { page-break-after: auto; }
        .waybill-header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #111; padding-bottom: 16px; }
        .brand { font-size: 28px; font-weight: 900; letter-spacing: 1px; }
        .label { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #555; }
        .status { border: 1px solid #111; padding: 8px 12px; font-size: 12px; font-weight: 700; text-transform: uppercase; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 28px; padding: 22px 0; }
        .block p { margin: 5px 0; line-height: 1.45; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th, td { border-bottom: 1px solid #ccc; padding: 12px 8px; text-align: left; }
        th { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
        td:last-child, th:last-child { text-align: right; }
        .total { display: flex; justify-content: space-between; align-items: center; border-top: 2px solid #111; margin-top: 18px; padding-top: 16px; font-size: 20px; font-weight: 700; }
        .empty { background: #fff; border: 1px solid #bbb; padding: 40px; text-align: center; }
        @media (max-width: 640px) { main { padding: 12px; } .grid { grid-template-columns: 1fr; gap: 16px; } .waybill { padding: 16px; } }
        @media print { body { background: #fff; } main { max-width: none; padding: 0; } .no-print { display: none !important; } .waybill { border: 0; margin: 0; padding: 10mm 0; } }
    </style>
</head>
<body>`r
@include('partials.demo-notice')
<main>
    <header class="toolbar no-print">
        <div>
            <h1>Order Waybills</h1>
            <div class="label">{{ $orders->count() }} matching order(s){{ $status !== '' ? ' · ' . $status : '' }}</div>
        </div>
        <button type="button" onclick="window.print()">Print these waybills</button>
    </header>

    @forelse($orders as $order)
        <article class="waybill">
            <div class="waybill-header">
                <div>
                    <div class="brand">SOLÉA FASHION CO.</div>
                    <div class="label">Packing waybill</div>
                </div>
            </div>

            <div class="grid">
                <section class="block">
                    <div class="label">Ship to</div>
                    <p><strong>{{ $order['customer_name'] }}</strong></p>
                    <p>{{ $order['customer_email'] }}</p>
                    <p>{{ $order['street_address'] }}</p>
                    <p>{{ trim($order['city'] . ' ' . $order['zip_code']) }}</p>
                    <p>Phone: {{ $order['phone'] }}</p>
                </section>
                <section class="block">
                    <div class="label">Order details</div>
                    <p><strong>#{{ $order['reference'] }}</strong></p>
                    <p>Recorded: {{ $order['date'] }}</p>
                    <p>Payment: {{ $order['payment'] }}</p>
                </section>
            </div>

            <table>
                <thead><tr><th>Item</th><th>Variant</th><th>Qty</th><th>Unit price</th><th>Line total</th></tr></thead>
                <tbody>
                @foreach($order['items'] as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['size'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>&#8369;{{ number_format($item['price'], 2) }}</td>
                        <td>&#8369;{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="total"><span>Order total</span><span>&#8369;{{ number_format($order['total'], 2) }}</span></div>
        </article>
    @empty
        <section class="empty">No orders match the current search and status filters.</section>
    @endforelse
</main>
<script>window.addEventListener('load', function () { window.print(); });</script>
</body>
</html>
