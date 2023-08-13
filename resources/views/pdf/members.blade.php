<!DOCTYPE html>
<html>

<head>
    <title>Voters Register</title>
    <style>
        /* Define styles for the PDF layout */
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }

        .pdf-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .table-container {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table-header {
            background-color: #f5f5f5;
        }

        .table-header th {
            padding: 10px;
            text-align: left;
        }

        .table-body td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <div class="pdf-container">
        <div style="text-align: center">
            <img src="{{ public_path('logo.png') }}" style="height:128px; margin-bottom: 0px" />
        </div>
        <h1 style="text-align:center; text-transform: uppercase;">Voters Register</h1>
        <table class="table-container">
            <thead class="table-header">
                <tr>
                    <th></th>
                    <th>NAME</th>
                    <th>PHONE</th>
                </tr>
            </thead>
            <tbody class="table-body">
                @foreach ($members as $member)
                    <tr>
                        <td style="text-align: right">{{ $loop->iteration }}.</td>
                        <td>
                            <div>{{ $member->name }}</div>
                        </td>
                        <td>{{ $member->phone }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
