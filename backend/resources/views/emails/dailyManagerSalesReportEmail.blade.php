<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Extrato diário de comissões</title>
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
        <h4 class="title">Olá, {{ $managerName }}!</h4>
        <p class="commission-summary">Valor total em vendas: <strong>R$ {{ $formatCurrency($salesAmount) }}</strong></p>
        
        <table class="sales-table">
            <thead>
                <tr>
                    <th>Venda</th>
                    <th>data</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                    <tr>
                        <td>{{ $sale['description'] }}</td>
                        <td>{{ date('d/m/Y', strtotime($sale['sale_date'])) }}</td>
                        <td>R$ {{ $formatCurrency($sale['amount']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>