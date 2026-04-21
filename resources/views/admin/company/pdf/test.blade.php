<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Company Test PDF</title>

    @php
        $pageTopMargin = $showHeader ? 130 : 40;
        $pageBottomMargin = $showFooter ? 80 : 40;
    @endphp

    <style>
        @page {
            margin: {{ $pageTopMargin }}px 40px {{ $pageBottomMargin }}px 40px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
            margin: 0;
        }

        .page-header {
            position: fixed;
            top: -105px;
            left: 0;
            right: 0;
        }

        .page-footer {
            position: fixed;
            bottom: -55px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 11px;
            color: #444;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
        }

        .header-logo-cell {
            width: 30%;
            text-align: left;
        }

        .header-text-cell {
            width: 70%;
            text-align: left;
        }

        .header-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .header-text,
        .footer-text {
            font-size: 11px;
            line-height: 1.5;
            white-space: pre-line;
        }

        .logo-only-wrapper,
        .text-only-wrapper {
            text-align: center;
        }

        .logo-image {
            width: {{ $logoSizePercent }}%;
            height: auto;
        }

        .content {
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    @if ($showHeader)
        <div class="page-header">
            @if ($headerMode === 'logo_only')
                <div class="logo-only-wrapper">
                    @if ($logoDataUri)
                        <img
                            src="{{ $logoDataUri }}"
                            alt="Logo"
                            class="logo-image"
                        >
                    @endif
                </div>
            @elseif ($headerMode === 'text_only')
                <div class="text-only-wrapper">
                    @if (filled($headerTitle))
                        <div class="header-title">{{ $headerTitle }}</div>
                    @endif

                    @if (filled($headerText))
                        <div class="header-text">{{ $headerText }}</div>
                    @endif
                </div>
            @elseif ($headerMode === 'logo_and_text')
                <table class="header-table">
                    <tr>
                        <td class="header-logo-cell">
                            {{-- {{ dd($logoDataUri); }} --}}
                            @if ($logoDataUri)
                                <img
                                    src="{{ $logoDataUri }}"
                                    alt="Logo"
                                    class="logo-image"
                                >
                            @endif
                        </td>
                        <td class="header-text-cell">
                            @if (filled($headerTitle))
                                <div class="header-title">{{ $headerTitle }}</div>
                            @endif

                            @if (filled($headerText))
                                <div class="header-text">{{ $headerText }}</div>
                            @endif
                        </td>
                    </tr>
                </table>
            @endif
        </div>
    @endif

    @if ($showFooter)
        <div class="page-footer">
            @if (filled($footerText))
                <div class="footer-text">{{ $footerText }}</div>
            @endif
        </div>
    @endif

    <main>
        <div class="content">
            {{ $contentText }}
        </div>
    </main>
</body>

</html>
