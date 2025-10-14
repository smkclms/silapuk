<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f7f7f7;
        }

        h1 {
            color: #007bff;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e6f0ff;
        }

        @media screen and (max-width: 600px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            tr {
                margin-bottom: 15px;
            }

            th, td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            th::before, td::before {
                position: absolute;
                left: 15px;
                top: 12px;
                white-space: nowrap;
                font-weight: bold;
            }

            th:nth-of-type(1)::before { content: "Pengguna"; }
            th:nth-of-type(2)::before { content: "Total Pengeluaran"; }
            td:nth-of-type(1)::before { content: "Pengguna"; }
            td:nth-of-type(2)::before { content: "Total Pengeluaran"; }
        }
    </style>
</head>
<body>

    <h1>Laporan Bulanan</h1>

    <table>
        <thead>
            <tr>
                <th>Pengguna</th>
                <th>Total Pengeluaran</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($monthly_expenditure as $user => $data): ?>
    <?php
        // Pastikan $data adalah angka
        $jumlah = is_array($data) && isset($data['jumlah_pengeluaran'])
                    ? $data['jumlah_pengeluaran']
                    : (is_numeric($data) ? $data : 0);
    ?>
    <tr>
        <td><?= $user; ?></td>
        <td>Rp <?= number_format($jumlah, 0, ',', '.'); ?></td>
    </tr>
<?php endforeach; ?>

        </tbody>
    </table>

</body>
</html>
