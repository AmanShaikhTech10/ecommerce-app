<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Classy Invoice</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; color: #222; }
        .wrapper { padding: 40px; border: 2px double #444; }
        .header-section { text-align: center; margin-bottom: 20px; }
        .logo { max-width: 200px; max-height: 80px; margin-bottom: 10px; }
        .company-name { font-size: 32px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .company-details { font-size: 12px; margin-top: 5px; }
        .divider { border-top: 1px solid #444; margin: 20px 0; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { vertical-align: top; }
        .items { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .items th { border-bottom: 2px solid #444; border-top: 2px solid #444; padding: 10px 5px; text-align: left; }
        .items td { padding: 10px 5px; border-bottom: 1px dotted #ccc; }
        .right { text-align: right !important; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header-section">
            <?php if (!empty($logo_image)): ?>
                <img src="<?= $logo_image ?>" class="logo" alt="SwiftCart Logo">
            <?php else: ?>
                <div class="company-name">SwiftCart Stores Ltd.</div>
            <?php endif; ?>
            <div class="company-details">
                4th floor, Pinnacle pride, Kaizen Softservices, Tilak Rd, opp. Cosmos Bank, Ramashram Society, Sadashiv Peth, Pune - 411030, India<br>
                Email: info@kaizensoftservices.com | Phone: +91-9604302826<br>
                <strong>GSTIN:</strong> 22AAAAA0000A1Z5
            </div>
        </div>

        <div class="divider"></div>

        <table class="info-table">
            <tr>
                <td>
                    <strong>INVOICE TO:</strong><br>
                    <?= esc($user['name']) ?><br>
                    <?= esc($user['email']) ?>
                </td>
                <td class="right">
                    <strong>Invoice No:</strong> <?= $invoice_number ?><br>
                    <strong>Date:</strong> <?= date('d M Y', strtotime($date)) ?><br>
                    <strong>Order ID:</strong> #<?= $order_id ?>
                </td>
            </tr>
        </table>

        <table class="items">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="right">Unit Price</th>
                    <th class="right">Qty</th>
                    <th class="right">Amount</th>
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
                    <td colspan="3" class="right" style="padding-top: 20px;"><strong>Subtotal:</strong></td>
                    <td class="right" style="padding-top: 20px;">$<?= number_format($total, 2) ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="right"><strong>Grand Total:</strong></td>
                    <td class="right"><strong>$<?= number_format($total, 2) ?></strong></td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 50px; text-align: center; font-style: italic;">
            Thank you for your business.
        </div>
    </div>
</body>
</html>