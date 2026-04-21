<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>{{ $subjectLine }}</title>
</head>

<body style="margin:0; padding:0; background:#f4f4f7; font-family: Arial, Helvetica, sans-serif;">
    <table
        role="presentation"
        width="100%"
        cellspacing="0"
        cellpadding="0"
        border="0"
    >
        <tr>
            <td
                align="center"
                style="padding:30px 15px;"
            >
                <table
                    role="presentation"
                    width="600"
                    cellspacing="0"
                    cellpadding="0"
                    border="0"
                    style="background:#ffffff;"
                >
                    <tr>
                        <td style="background:#0d6efd; color:#ffffff; padding:20px 30px;">
                            @if ($logoUrl)
                                <img
                                    src="{{ $logoUrl }}"
                                    alt="Logo"
                                    style="max-height:50px; margin-bottom:10px;"
                                >
                            @endif

                            <h1 style="margin:0; font-size:22px; font-weight:bold;">
                                {{ $companyName ?: 'System Notification' }}
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px;">
                            @yield('content')
                        </td>
                    </tr>

                    <tr>
                        <td style="background:#f8f9fa; padding:20px 30px; font-size:12px; color:#6c757d;">

                            <p style="margin:0 0 8px;">
                                This is an automated notification from {{ $companyName ?: config('app.name') }}.
                            </p>
                            <p style="margin:0 0 8px;">
                                For assistance, please contact support.
                            </p>
                            <p style="margin:0;">
                                &copy; {{ date('Y') }} {{ $companyName ?: config('app.name') }}. All rights
                                reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
