@extends('bg::mail.mail_layout')


@section('mail_title')
    Monthly Statement - {{ config('bank_ussd.bank_name') }}
@endsection

@section('content')
    <table class="s-4 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;"
        width="100%">
        <tbody>
            <tr>
                <td style="line-height: 16px; font-size: 16px; width: 100%; height: 16px; margin: 0;" align="left"
                    width="100%" height="16">
                    &#160;
                </td>
            </tr>
        </tbody>
    </table>
    <p class="" style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left">
        Dear {{ $email_body['account_name'] }},
        <br />
        <br />
        Your monthly consolidated account e-statement for the period {{ $email_body['from_date'] }} to:
        {{ $email_body['to_date'] }}
        is ready and attached to this email.
        <br />
        <br />
        Please note that this is a consolidated account statement file is password protected. The password
        protocol is your 6 digit customer ID. If you do not know your 6 digit customer ID, please contact the nearest branch
        for assistance.
        <br />
        <br />
        Should you have any query or feedback, please reach out to our 24/7 contact center via phone
        ({{ $email_body['support_phone_primary'] }}, {{ $email_body['support_phone_secondary'] }}) or
        email <a href="mailto:info@salaammfbank.co.ke">info@salaammfbank.co.ke</a>
    </p>

    <p>
        <br /><br />
        Thank you. <br />
        {{ config('bank_ussd.bank_name') }}
    </p>
    <table class="s-4 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;"
        width="100%">
        <tbody>
            <tr>
                <td style="line-height: 16px; font-size: 16px; width: 100%; height: 16px; margin: 0;" align="left"
                    width="100%" height="16">
                    &#160;
                </td>
            </tr>
        </tbody>
    </table>
    <table class="ax-center" role="presentation" align="center" border="0" cellpadding="0" cellspacing="0"
        style="margin: 0 auto;">
        <tbody>
            <tr>
                <td style="line-height: 24px; font-size: 16px; margin: 0;" align="left">
                    <img class="w-24"
                        src="{{ config('bank_ussd.bank_logo', 'https://assets.bootstrapemail.com/logos/light/square.png') }}"
                        style="height: auto; line-height: 100%; outline: none; text-decoration: none; display: block; width: 96px; border-style: none; border-width: 0;"
                        width="96">
                </td>
            </tr>
        </tbody>
    </table>
@endsection
