<table>
    <thead>
        <tr>
            <th>Nama Member</th>
            <th>Nama Kolektor</th>
            <th>Tipe Transaksi</th>
            <th>Jumlah</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $t)
            <tr>
                <td>{{ $t->member->user->name ?? '-' }}</td>
                <td>{{ $t->collector->user->name ?? '-' }}</td>
                <td>{{ ucfirst($t->tipe_transaksi) }}</td>
                <td>{{ number_format($t->jumlah, 0, ',', '.') }}</td>
                <td>{{ $t->created_at->format('Y-m-d') }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2"><strong>Total Debit:</strong></td>
            <td colspan="3">{{ number_format($totalDebit, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Total Kredit:</strong></td>
            <td colspan="3">{{ number_format($totalKredit, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>
