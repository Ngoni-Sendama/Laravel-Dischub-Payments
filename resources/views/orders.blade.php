<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Transactions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4">Payment Transactions</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Status</th>
                    <th>Amount</th>
                    <th>Currency</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->order_id }}</td>
                    <td>
                        <span class="badge
                            @if($transaction->status == 'success') bg-success
                            @elseif($transaction->status == 'failed') bg-danger
                            @else bg-warning @endif">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </td>
                    <td>${{ number_format($transaction->amount, 2) }}</td>
                    <td>{{ $transaction->currency }}</td>
                    <td>
                        <form action="{{ route('payment.status', $transaction->order_id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">Refresh Status</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
