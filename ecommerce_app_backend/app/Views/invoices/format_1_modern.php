<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>SwiftCart Store Ltd - Modern Invoice</title>
    <style>
        /* Define strict base constraints for the entire PDF canvas sheet layout */
        @page {
            size: a4 portrait;
            margin: 0mm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 11px;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }

        /* Explicitly define fixed dimensions matching standard pixel layouts of A4 print canvases */
        .invoice-wrapper {
            position: relative;
            width: 100%;
            height: 1120px; /* Maps precisely to one full page container block height */
            box-sizing: border-box;
        }

        /* Position both elements with absolute dimensions to prevent line overflow wraps */
        .invoice-owner {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 530px;
            padding: 30px 40px;
            box-sizing: border-box;
        }

        .invoice-customer {
            position: absolute;
            top: 570px;
            left: 0;
            right: 0;
            height: 530px;
            padding: 30px 40px;
            box-sizing: border-box;
        }

        .cut-line-container {
            position: absolute;
            top: 545px;
            left: 0;
            right: 0;
            text-align: center;
            color: #888;
            font-size: 10px;
            border-top: 1px dashed #999;
            padding-top: 4px;
        }

        .header { width: 100%; border-bottom: 2px solid #007185; padding-bottom: 5px; margin-bottom: 8px; }
        .header td { border: none; vertical-align: top; }
        .title { font-size: 20px; color: #007185; font-weight: bold; }

        .table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .table th, .table td { padding: 5px 6px; border-bottom: 1px solid #ddd; text-align: left; }
        .table th { background: #f8f8f8; color: #333; font-weight: bold; }

        .right { text-align: right !important; }
        .total { font-size: 14px; font-weight: bold; color: #B12704; border-bottom: none !important; }
        .copy-type { text-align: center; font-size: 11px; color: #777; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 3px; font-weight: bold; }

        /* Flex box alternative layout for payment positioning */
        .summary-container {
            width: 100%;
            margin-top: 10px;
        }
        .qr-box {
            width: 120px;
            text-align: left;
            vertical-align: top;
        }
    </style>
</head>
<body>
    <div class="invoice-wrapper">

        <!-- ==================== CUSTOMER COPY ==================== -->
        <div class="invoice-owner">
            <div class="copy-type">OWNER COPY</div>
            <table class="header">
                <tr>
                    <td style="width: 50%;">
                        <span class="title">INVOICE</span><br>
                        <strong>Number:</strong> <?= $invoice_number ?><br>
                        <strong>Date:</strong> <?= date('F j, Y', strtotime($date)) ?>
                    </td>
                    <td class="right" style="width: 50%;">
                        <strong>Billed To:</strong><br>
                        <?= esc($user['name']) ?><br>
                        <?= esc($user['email']) ?>
                    </td>
                </tr>
            </table>

            <table class="table">
                <thead>
                    <tr>
                        <th>Item Description</th>
                        <th class="right" style="width: 15%;">Price</th>
                        <th class="right" style="width: 10%;">Qty</th>
                        <th class="right" style="width: 15%;">Subtotal</th>
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
                </tbody>
            </table>

            <!-- Bottom summary displaying payment options side-by-side -->
            <table class="summary-container">
                <tr>
                    <td class="qr-box">
                        <?php if (!empty($qrImageBase64)): ?>
                            <img src="<?= $qrImageBase64 ?>" width="95" height="95" alt="UPI QR Code" />
                        <?php endif; ?>
                    </td>
                    <td class="right" style="vertical-align: bottom;">
                        <span class="total">Grand Total: &nbsp;$<?= number_format($total, 2) ?></span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- ==================== CUT LINE DECORATION ==================== -->
        <div class="cut-line-container">✂ - - - - - - - - Please cut here - - - - - - - - ✂</div>

        <!-- ==================== OWNER COPY ==================== -->
        <div class="invoice-customer">
            <div class="copy-type">CUSTOMER COPY</div>
            <table class="header">
                <tr>
                    <td style="width: 50%;">
                        <span class="title">INVOICE</span><br>
                        <strong>Number:</strong> <?= $invoice_number ?><br>
                        <strong>Date:</strong> <?= date('F j, Y', strtotime($date)) ?>
                    </td>
                    <td class="right" style="width: 50%;">
                        <strong>Billed To:</strong><br>
                        <?= esc($user['name']) ?><br>
                        <?= esc($user['email']) ?>
                    </td>
                </tr>
            </table>

            <table class="table">
                <thead>
                    <tr>
                        <th>Item Description</th>
                        <th class="right" style="width: 15%;">Price</th>
                        <th class="right" style="width: 10%;">Qty</th>
                        <th class="right" style="width: 15%;">Subtotal</th>
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
                </tbody>
            </table>

            <!-- Bottom summary displaying payment options side-by-side -->
            <table class="summary-container">
                <tr>
                    <td class="qr-box">
                        <?php if (!empty($qrImageBase64)): ?>
                            <img src="<?= $qrImageBase64 ?>" width="95" height="95" alt="UPI QR Code" />
                        <?php endif; ?>
                    </td>
                    <td class="right" style="vertical-align: bottom;">
                        <span class="total">Grand Total: &nbsp;$<?= number_format($total, 2) ?></span>
                    </td>
                </tr>
            </table>
        </div>

    </div>
</body>
</html>
