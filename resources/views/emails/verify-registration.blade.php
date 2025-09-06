<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('auth.verify_email_subject') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9fafb;
        }
        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: 300;
            color: #ec4899;
            margin-bottom: 10px;
        }
        .title {
            font-size: 24px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            background-color: #ec4899;
            color: white;
            padding: 14px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            text-align: center;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #db2777;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            color: #6b7280;
            text-align: center;
        }
        .warning {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            font-size: 14px;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ config('constants.shop_name') }}</div>
            <h1 class="title">{{ __('auth.verify_email_title') }}</h1>
        </div>

        <div class="content">
            <p>{{ __('auth.email_greeting') }}</p>
            
            <p>{{ __('auth.email_verification_instruction') }}</p>

            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="button">
                    {{ __('auth.verify_email_button') }}
                </a>
            </div>

            <div class="warning">
                <strong>{{ __('auth.important') }}:</strong> {{ __('auth.verification_link_expires') }}
            </div>

            <p>{{ __('auth.email_manual_copy') }}</p>
            <p style="word-break: break-all; background-color: #f3f4f6; padding: 10px; border-radius: 4px; font-family: monospace; font-size: 12px;">
                {{ $verificationUrl }}
            </p>

            <p>{{ __('auth.email_not_requested') }}</p>
        </div>

        <div class="footer">
            <p>{{ __('auth.email_footer', ['shop' => config('constants.shop_name')]) }}</p>
            <p>{{ config('constants.shop_email') }} | {{ config('constants.shop_phone') }}</p>
        </div>
    </div>
</body>
</html>
