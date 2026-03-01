<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('Monthly Report') }} - {{ $date }}</title>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.5; }
        .header { border-bottom: 2px solid #fbbf24; padding-bottom: 10px; margin-bottom: 20px; }
        .title { font-size: 24px; font-weight: bold; }
        .summary-box { background: #f9fafb; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .grid { display: table; width: 100%; }
        .col { display: table-cell; width: 33%; padding: 10px; }
        .label { font-size: 12px; color: #6b7280; text-transform: uppercase; }
        .value { font-size: 18px; font-weight: bold; }
        .diff { font-size: 10px; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f3f4f6; text-align: left; padding: 10px; border-bottom: 1px solid #e5e7eb; }
        td { padding: 10px; border-bottom: 1px solid #e5e7eb; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ config('app.name') }} - {{ __('Monthly Report') }}</div>
        <div>{{ $walletName }} | {{ $date }}</div>
    </div>

    <div class="summary-box">
        <div class="grid">
            <div class="col">
                <div class="label">{{ __('Total Income') }}</div>
                <div class="value" style="color: #059669;">${{ number_format($report['current']['total_income'], 2) }}</div>
                <div class="diff" style="color: {{ $report['comparison']['income_diff'] >= 0 ? '#059669' : '#dc2626' }};">
                    {{ $report['comparison']['income_diff'] >= 0 ? '↑' : '↓' }} {{ number_format(abs($report['comparison']['income_percentage']), 1) }}%
                </div>
            </div>
            <div class="col">
                <div class="label">{{ __('Total Expenses') }}</div>
                <div class="value" style="color: #dc2626;">${{ number_format($report['current']['total_expenses'], 2) }}</div>
                <div class="diff" style="color: {{ $report['comparison']['expense_diff'] <= 0 ? '#059669' : '#dc2626' }};">
                    {{ $report['comparison']['expense_diff'] >= 0 ? '↑' : '↓' }} {{ number_format(abs($report['comparison']['expense_percentage']), 1) }}%
                </div>
            </div>
            <div class="col">
                <div class="label">{{ __('Net Flow') }}</div>
                <div class="value" style="color: {{ $report['current']['net_flow'] >= 0 ? '#059669' : '#dc2626' }};">
                    ${{ number_format($report['current']['net_flow'], 2) }}
                </div>
            </div>
        </div>
    </div>

    <h3>{{ __('Expenses by User') }}</h3>
    <table>
        <thead>
            <tr>
                <th>{{ __('User') }}</th>
                <th>{{ __('Amount') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report['expenses_by_user'] as $userData)
                <tr>
                    <td>{{ $userData['name'] }}</td>
                    <td>${{ number_format($userData['amount'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __('Generated on') }} {{ now()->format('d/m/Y H:i') }} | {{ config('app.name') }} SaaS
    </div>
</body>
</html>
