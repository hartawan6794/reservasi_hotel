<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice #{{ $editData->code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            background: #f5f5f5;
            padding: 20px;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 40px;
            text-align: center;
        }
        .invoice-header h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .invoice-header p {
            font-size: 13px;
            opacity: 0.9;
            margin: 3px 0;
        }
        .invoice-body {
            padding: 40px;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }
        .info-box {
            flex: 1;
            min-width: 200px;
            margin-bottom: 20px;
        }
        .info-box h3 {
            font-size: 12px;
            text-transform: uppercase;
            color: #999;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
        .info-box p {
            font-size: 14px;
            color: #333;
            margin: 5px 0;
        }
        .info-box strong {
            color: #667eea;
        }
        .invoice-details {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 25px;
            margin-bottom: 30px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            color: #666;
            font-size: 14px;
        }
        .detail-value {
            color: #333;
            font-size: 14px;
            font-weight: 500;
        }
        .price-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .price-table thead {
            background: #667eea;
            color: white;
        }
        .price-table th {
            padding: 15px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        .price-table td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
        }
        .price-table tbody tr:hover {
            background: #f8f9fa;
        }
        .total-section {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 25px;
            margin-top: 30px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 14px;
        }
        .total-row.grand-total {
            border-top: 2px solid #667eea;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 18px;
            font-weight: 600;
            color: #667eea;
        }
        .total-label {
            color: #666;
        }
        .total-value {
            color: #333;
            font-weight: 500;
        }
        .grand-total .total-value {
            color: #667eea;
            font-size: 20px;
        }
        .invoice-footer {
            background: #f8f9fa;
            padding: 30px 40px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .invoice-footer p {
            color: #666;
            font-size: 12px;
            margin: 5px 0;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-primary {
            background: #667eea;
            color: white;
        }
        .badge-success {
            background: #10b981;
            color: white;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .invoice-container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <h1>INVOICE</h1>
            <p>Booking Code: {{ $editData->code }}</p>
            <p style="margin-top: 5px;">Date: {{ $editData->created_at->format('d M Y') }}</p>
        </div>

        <!-- Body -->
        <div class="invoice-body">
            <!-- Invoice Info -->
            <div class="invoice-info">
                <div class="info-box">
                    <h3>Guest Information</h3>
                    <p><strong>Name:</strong> {{ $editData->name }}</p>
                    <p><strong>Email:</strong> {{ $editData->email }}</p>
                    <p><strong>Phone:</strong> {{ $editData->phone ?? 'N/A' }}</p>
                </div>
                <div class="info-box">
                    <h3>Booking Details</h3>
                    <p><strong>Room Type:</strong> {{ $editData->room->type->name }}</p>
                    <p><strong>Number of Rooms:</strong> {{ $editData->number_of_rooms }}</p>
                    <p><strong>Total Nights:</strong> {{ $editData->total_night }} night(s)</p>
                </div>
            </div>

            <!-- Booking Dates -->
            <div class="invoice-details">
                <div class="detail-row">
                    <span class="detail-label">Check In Date</span>
                    <span class="detail-value badge badge-primary">{{ $editData->check_in }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Check Out Date</span>
                    <span class="detail-value badge badge-success">{{ $editData->check_out }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Method</span>
                    <span class="detail-value">{{ $editData->payment_method }}</span>
                </div>
            </div>

            <!-- Price Table -->
            <table class="price-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>{{ $editData->room->type->name }}</strong><br>
                            <small style="color: #999;">
                                {{ $editData->number_of_rooms }} room(s) × {{ $editData->total_night }} night(s) × {{ rupiah($editData->actual_price) }}/night
                            </small>
                        </td>
                        <td style="text-align: right;">{{ rupiah($editData->subtotal) }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Total Section -->
            <div class="total-section">
                <div class="total-row">
                    <span class="total-label">Subtotal</span>
                    <span class="total-value">{{ rupiah($editData->subtotal) }}</span>
                </div>
                <div class="total-row">
                    <span class="total-label">Discount</span>
                    <span class="total-value">- {{ rupiah($editData->discount) }}</span>
                </div>
                <div class="total-row grand-total">
                    <span class="total-label">Grand Total</span>
                    <span class="total-value">{{ rupiah($editData->total_price) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <p><strong>Thank you for your booking!</strong></p>
            <p>If you have any questions, please contact our support team.</p>
            <p style="margin-top: 15px; color: #999;">This is an automated invoice. No signature required.</p>
        </div>
    </div>
</body>
</html>
