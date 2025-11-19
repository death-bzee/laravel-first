<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Document' }}</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('{{ storage_path('fonts/DejaVuSans.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
        }

		.container {
			font-size: 12px;
		}
        .font-bold {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-2xl {
            font-size: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            border: 1px solid #d1d5db;
            padding: 0.5rem;
        }

        .table thead th {
            background-color: #f3f4f6;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .table td:first-child {
            font-weight: bold;
        }
        .img-rounded {
            border-radius: 50%;
            width: 8rem; /* 32px */
            height: 8rem; /* 32px */
        }
    </style>
</head>
<body>
