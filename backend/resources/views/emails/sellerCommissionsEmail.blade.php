<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Extrato de Comissão</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }

        .container {
            padding: 20px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
        }

        .commission-summary {
            margin-top: 10px;
            font-size: 16px;
        }

        .sales-table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }

        .sales-table th, .sales-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .sales-table th {
            background-color: #f5f5f5;
        }

        .footer {
            margin-top: 30px;
        }
    </style>
</head>
<?php
    $formatCurrency = function($amount) {
        return number_format($amount, 2, ',', '.');
    }
?>
<body>
    <div class="container">
        <h4 class="title">Olá, {{ $seller['name'] }}!</h4>
        <p class="commission-summary">Sua comissão total é de <strong>R$ {{ $formatCurrency($commissionAmount) }}</strong>. Abaixo você verá o extrato detalhado.</p>
        
        <table class="sales-table">
            <thead>
                <tr>
                    <th>Venda</th>
                    <th>data</th>
                    <th>Valor</th>
                    <th>Comissão</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commissions as $commission)
                    <tr>
                        <td>{{ $commission['sale']['description'] }}</td>
                        <td>{{ date('d/m/Y', strtotime($commission['sale']['sale_date'])) }}</td>
                        <td>R$ {{ $formatCurrency($commission['sale']['amount']) }}</td>
                        <td>R$ {{ $formatCurrency($commission['commission']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>