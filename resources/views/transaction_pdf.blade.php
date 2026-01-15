<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
      <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { text-align: left; border: 1px solid #ddd; padding: 6px; }
        th { background: #f3f4f6; }
        h2 { text-align: center; }
    </style>
</head>
<body>
<h2>Transaction Statement</h2>
<p><strong>User:</strong> {{ $user->name }}</p>
<p><strong>Date:</strong> {{ now()->format('d M Y') }}</p>
    
<table border="1" width="100%">
    <tr>
        <th>Trx_iD</th>
        <th>Amount</th>       
        <th>Transaction Status </th>
        <th>Credited Amount </th>
        <th>Date </th>
    </tr>
    @foreach ($transactions as $index => $transaction)
        <tr>
            <td>{{ $transaction->trx_iD }}</td>
            <td>{{ $transaction->amount }}</td>
            <td>{{ $transaction->transaction_status }}</td>
            <td>{{ $transaction->credited_amount }}</td>
            <td>{{ $transaction->created_at }}</td>
        </tr>
    @endforeach
</table>

</body>
</html>
</body>
</html>