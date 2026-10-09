<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\CartItemModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use Dompdf\Dompdf;
use Dompdf\Options;

class OrderController extends ResourceController
{
    protected $format = 'json';

    public function create()
    {
        $userId = session()->get('user_id');
        //demo
        // $json = $this->request->getJSON();
        // $invoiceFormat = $json->invoice_format ?? 4;
        //demo

        $cartModel = new CartModel();
        $cartItemModel = new CartItemModel();
        $orderModel = new OrderModel();
        $orderItemModel = new OrderItemModel();
        $userModel = new UserModel();

        $user = $userModel->find($userId);
        $cart = $cartModel->where('user_id', $userId)->first();

        if (!$cart) {
            return $this->respond(['status' => false, 'message' => 'Cart not found'], 404);
        }

        $cartItems = $cartItemModel->where('cart_id', $cart['id'])->findAll();
        if (empty($cartItems)) {
            return $this->respond(['status' => false, 'message' => 'Cart is empty'], 400);
        }

        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += ($item['quantity'] * $item['product_price']);
        }

        //demo

        $payment = session()->get('mock_payment');

        if (
            !$payment ||
            $payment['status'] !== 'verified' ||
            $payment['user_id'] != $userId ||
            abs((float) $payment['amount'] - (float) $totalAmount) > 0.01
        ) {
            return $this->respond([
                'status' => false,
                'message' => 'Payment has not been verified.'
            ], 400);
        }

        // Use the invoice format selected when checkout began.
        $invoiceFormat = (int) ($payment['invoice_format'] ?? 4);

        //demo

        $db = \Config\Database::connect();
        $db->transStart();

        $orderId = $orderModel->insert([
            'user_id' => $userId,
            'total_amount' => $totalAmount,
            'status' => 'completed'
        ]);

        foreach ($cartItems as $item) {
            $orderItemModel->insert([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'product_title' => $item['product_title'],
                'product_price' => $item['product_price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['quantity'] * $item['product_price']
            ]);
        }

        $cartItemModel->where('cart_id', $cart['id'])->delete();
        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->respond(['status' => false, 'message' => 'Failed to process order'], 500);
        }

        //demo
        session()->remove('mock_payment');
        //demo

        $invoiceDir = WRITEPATH . 'uploads/invoices';
        if (!is_dir($invoiceDir)) {
            mkdir($invoiceDir, 0777, true);
        }

        $invoiceNumber = 'INV-' . str_pad($orderId, 6, '0', STR_PAD_LEFT);
        $invoiceFilename = $invoiceNumber . '.pdf';
        $invoicePath = $invoiceDir . '/' . $invoiceFilename;

        // Pre-fetch images as Base64 for Dompdf to prevent rendering failures
        $qrImageBase64 = '';
        $logoBase64 = '';

        if ($invoiceFormat == 1) {
            $qrImageBase64 = '';
            $logoBase64 = '';
        } elseif ($invoiceFormat == 2) {
            // Place your logo.png in the backend 'public' folder
            $logoPath = FCPATH . 'logo.png';
            if (file_exists($logoPath)) {
                $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
            }
        } elseif ($invoiceFormat == 3) {
            // 1. Set your raw UPI ID
            $myUpiId = "9156891986@kotakbank";
            $upiUrl = "upi://pay?pa={$myUpiId}";

            // 2. Pass a plain array directly into the main QRCode class constructor
            $qrcode = new \chillerlan\QRCode\QRCode([
                'version' => 4,
                'eccLevel' => \chillerlan\QRCode\Common\EccLevel::L,
                'outputInterface' => \chillerlan\QRCode\Output\QRGdImagePNG::class,
            ]);

            // 3. Render directly to a Base64 string layout
            $qrImageBase64 = $qrcode->render($upiUrl);
        } elseif ($invoiceFormat == 4) {
            // 1. Keep your exact custom informational string layout
            $info = "Inv: {$invoiceNumber} | Total: \${$totalAmount} | Customer: {$user['name']}";

            // 2. Generate the QR code using a safe, structurally valid config array
            $qrcode = new \chillerlan\QRCode\QRCode([
                'version' => 5,
                'eccLevel' => \chillerlan\QRCode\Common\EccLevel::L,
                'outputInterface' => \chillerlan\QRCode\Output\QRGdImagePNG::class,
                'addQuietzone' => true,        // Adds a white border so Google Lens can separate it from text
                'imageTransparency' => false,  // Disables transparency so background defaults to solid white
                'scale' => 5,                  // Increases pixel resolution sharpness inside the Dompdf canvas
            ]);

            // 3. Render directly into your base64 string variable 100% offline
            $qrImageBase64 = $qrcode->render($info);
        }

        $viewName = match ((int) $invoiceFormat) {
            1 => 'invoices/format_1_modern',
            2 => 'invoices/format_2_classy',
            3 => 'invoices/format_3_royal',
            default => 'invoices/format_4_standard',
        };

        // Note: Map 'qr_image' => $qrImageBase64 to your layout views
        $html = view($viewName, [
            'order_id' => $orderId,
            'invoice_number' => $invoiceNumber,
            'date' => date('Y-m-d H:i:s'),
            'user' => $user,
            'items' => $cartItems,
            'total' => $totalAmount,
            'qr_image' => $qrImageBase64,
            'logo_image' => $logoBase64
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        file_put_contents($invoicePath, $dompdf->output());

        $orderModel->update($orderId, ['invoice_path' => $invoiceFilename]);

        $emailService = \Config\Services::email();
        $config = [
            'protocol' => 'smtp',
            'SMTPHost' => getenv('GMAIL_HOST') ?: 'smtp.gmail.com',
            'SMTPPort' => (int) (getenv('GMAIL_PORT') ?: 587),
            'SMTPUser' => getenv('GMAIL_USERNAME'),
            'SMTPPass' => getenv('GMAIL_PASSWORD'),
            'SMTPCrypto' => 'tls',
            'mailType' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n"
        ];

        $emailService->initialize($config);
        $emailService->setFrom(getenv('GMAIL_FROM_EMAIL'), getenv('GMAIL_FROM_NAME'));
        $emailService->setTo($user['email']);
        $emailService->setSubject('Order Confirmation - Invoice #' . $invoiceNumber);

        $emailMessage = "
            <h2>Thank you for your order!</h2>
            <p>Hi " . esc($user['name']) . ",</p>
            <p>Your order <strong>#" . $orderId . "</strong> has been successfully placed.</p>
            <p><strong>Total Amount:</strong> $" . number_format($totalAmount, 2) . "</p>
            <p>Please find your invoice attached to this email.</p>
        ";

        $emailService->setMessage($emailMessage);
        $emailService->attach($invoicePath);

        $emailSent = $emailService->send();

        $responseMessage = 'Order placed successfully';
        if (!$emailSent) {
            $responseMessage = 'Order placed successfully, but failed to send email invoice.';
        }

        return $this->respond([
            'status' => true,
            'message' => $responseMessage,
            'data' => [
                'order_id' => $orderId,
                'total_amount' => $totalAmount,
                'invoice_file' => $invoiceFilename,
                'email_sent' => $emailSent
            ]
        ], 201);
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $orderModel = new OrderModel();

        $orders = $orderModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->findAll();

        return $this->respond([
            'status' => true,
            'message' => 'Orders retrieved successfully',
            'data' => $orders
        ]);
    }

    public function downloadInvoice($id)
    {
        $userId = session()->get('user_id');
        $orderModel = new OrderModel();

        // Ensure the requested invoice exists and belongs to the authenticated user
        $order = $orderModel->where(['id' => $id, 'user_id' => $userId])->first();

        // Cleaned up the escaping/syntax break error here
        if (!$order || empty($order['invoice_path'])) {
            return $this->failNotFound('Invoice not found');
        }

        $filePath = WRITEPATH . 'uploads/invoices/' . $order['invoice_path'];

        if (!file_exists($filePath)) {
            return $this->failNotFound('Physical invoice file missing from server storage');
        }

        // Return the binary file stream to the client safely using CodeIgniter response traits
        return $this->response->download($filePath, null)->setFileName($order['invoice_path']);
    }
}
