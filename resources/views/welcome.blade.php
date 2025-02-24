<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Dischub API</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white shadow-lg p-6 rounded-lg w-96">
        <h2 class="text-xl font-semibold mb-4 text-center">Dischub Payment Test</h2>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 mb-4 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('dischub.order') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium">Order ID</label>
                <input type="number" name="order_id" class="w-full p-2 border rounded" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Sender Email</label>
                <input type="email" name="sender" class="w-full p-2 border rounded" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Recipient Email</label>
                <input type="email" name="recipient" class="w-full p-2 border rounded" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Amount (Max: 480 USD)</label>
                <input type="number" name="amount" class="w-full p-2 border rounded" step="0.01" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Currency</label>
                <select name="currency" class="w-full p-2 border rounded" required>
                    <option value="USD">USD</option>
                    <option value="ZWG">ZWG</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-500 text-white w-full py-2 rounded hover:bg-blue-600">
                Initiate Payment
            </button>
        </form>
    </div>
</body>
</html>
