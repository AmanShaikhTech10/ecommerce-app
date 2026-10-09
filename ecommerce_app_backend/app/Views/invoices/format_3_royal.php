<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title> Invoice</title>
    <style>
        body { font-family: 'Georgia', serif; color: #1a2a3a; background: #faf9f6; }
        .header { background-color: #1a2a3a; color: #d4af37; padding: 30px; text-align: center; border-bottom: 5px solid #d4af37; }
        .header h1 { margin: 0; font-size: 36px; letter-spacing: 2px; text-transform: uppercase; }
        .content { padding: 40px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        .table th { background-color: #1a2a3a; color: #d4af37; padding: 12px; text-align: left; }
        .table td { padding: 12px; border-bottom: 1px solid #ddd; }
        .right { text-align: right !important; }
        .total-row td { background-color: #f1ebd9; font-weight: bold; font-size: 18px; }
        .qr-section { margin-top: 40px; text-align: center; border: 2px solid #d4af37; padding: 20px; display: inline-block; width: 100%; box-sizing: border-box; }
        .qr-image { width: 150px; height: 150px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SwiftCart Stores</h1>
        <p>Premium Products & Services</p>
    </div>

    <div class="content">
        <table style="width: 100%;">
            <tr>
                <td>
                    <h3>Bill To:</h3>
                    <?= esc($user['name']) ?><br>
                    <?= esc($user['email']) ?>
                </td>
                <td class="right">
                    <h3>Invoice <?= $invoice_number ?></h3>
                    Date: <?= date('F j, Y', strtotime($date)) ?>
                </td>
            </tr>
        </table>

        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="right">Rate</th>
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
                <tr class="total-row">
                    <td colspan="3" class="right">Final Amount:</td>
                    <td class="right">$<?= number_format($total, 2) ?></td>
                </tr>
            </tbody>
        </table>

        <div class="qr-section">
            <h3 style="color: #1a2a3a; margin-top: 0;">Scan to Pay via UPI</h3>
            <?php if (!empty($qr_image)): ?>
                <img src="<?= $qr_image ?>" class="qr-image" alt="UPI QR Code"><br>
            <?php else: ?>
                <div style="width:150px; height:150px; border:1px solid #ccc; display:inline-block; line-height:150px; color:#999;">QR Unavailable</div><br>
            <?php endif; ?>
            <p style="margin-bottom: 0;">Amount due: <strong>$<?= number_format($total, 2) ?></strong></p>
        </div>
    </div>
</body>
</html>