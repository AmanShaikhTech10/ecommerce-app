<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice <?= $invoice_number ?></title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .store-name { font-size: 24px; font-weight: bold; color: #232f3e; }
        .details { margin-bottom: 30px; width: 100%; border-collapse: collapse; }
        .details td { padding: 5px; vertical-align: top; }
        .items { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items th, .items td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .items th { background-color: #f8f8f8; font-weight: bold; }
        .items .right { text-align: right; }
        .total-row td { font-weight: bold; font-size: 1.2em; background-color: #f8f8f8; }
    </style>
</head>
<body>
    <div class="header">
        <div class="store-name">SwiftCart Stores Ltd</div>
        <h2>INVOICE</h2>
    </div>

    <table class="details">
        <tr>
            <td>
                <strong>Billed To:</strong><br>
                <?= esc($user['name']) ?><br>
                <?= esc($user['email']) ?>
            </td>
            <td style="text-align: right;">
                <strong>Invoice Number:</strong> <?= $invoice_number ?><br>
                <strong>Order ID:</strong> #<?= $order_id ?><br>
                <strong>Date:</strong> <?= date('F j, Y', strtotime($date)) ?>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>Product Name</th>
                <th class="right">Price</th>
                <th class="right">Quantity</th>
                <th class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as$item): ?>
            <tr>
                <td><?= esc($item['product_title']) ?></td>
                <td class="right">$<?= number_format($item['product_price'], 2) ?></td>
                <td class="right"><?= $item['quantity'] ?></td>
                <td class="right">$<?= number_format($item['quantity'] *$item['product_price'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="3" class="right">Grand Total:</td>
                <td class="right">$<?= number_format($total, 2) ?></td>
            </tr>
        </tbody>
    </table>
</body>
</html>