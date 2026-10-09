<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Standard Invoice</title>
    <style>
        body { font-family: 'Arial', sans-serif; color: #333; }
        .header { display: table; width: 100%; margin-bottom: 30px; }
        .header-left { display: table-cell; vertical-align: top; }
        .header-right { display: table-cell; text-align: right; vertical-align: top; }
        .items { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items th, .items td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        .items th { background-color: #eee; font-weight: bold; }
        .right { text-align: right !important; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <h2>INVOICE</h2>
            <strong>Number:</strong> <?= $invoice_number ?><br>
            <strong>Date:</strong> <?= date('Y-m-d', strtotime($date)) ?><br><br>
            <strong>Customer:</strong><br>
            <?= esc($user['name']) ?><br>
            <?= esc($user['email']) ?>
        </div>
        <div class="header-right">
            <?php if (!empty($qr_image)): ?>
                <img src="<?= $qr_image ?>" alt="Invoice Info QR Code" style="width: 120px; height: 120px; border: 1px solid #ccc; padding: 5px;">
            <?php else: ?>
                <div style="width:120px; height:120px; border:1px solid #ccc; display:inline-block; line-height:120px; color:#999;">QR Unavailable</div>
            <?php endif; ?>
            <div style="font-size: 10px; margin-top: 5px; color: #777;">Scan for Summary</div>
        </div>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>Item</th>
                <th class="right">Price</th>
                <th class="right">Qty</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= esc($item['product_title']) ?></td>
                <td class="right">$<?= number_format($item['product_price'], 2) ?></td>
                <td class="right"><?= $item['quantity'] ?></td>
                <td class="right">$<?= number_format($item['quantity'] * $item['product_price'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" class="right" style="background-color: #eee; font-weight: bold;">Grand Total:</td>
                <td class="right" style="background-color: #eee; font-weight: bold;">$<?= number_format($total, 2) ?></td>
            </tr>
        </tbody>
    </table>
</body>
</html>