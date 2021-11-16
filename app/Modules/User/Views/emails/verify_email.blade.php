<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <style>
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>
</head>
<body>

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td>
            <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td class="header">
                        <a href="{{ config('app.url') }}" style="display: inline-block;">
                            {{ config('app.name') }}
                        </a>
                    </td>
                </tr>

                <!-- Email Body -->
                <tr>
                    <td class="body" width="100%" cellpadding="0" cellspacing="0">
                        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0"
                               role="presentation">
                            <!-- Body content -->
                            <tr>
                                <td class="content-cell">
                                    <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0"
                                           role="presentation">
                                        <tr>
                                            <td align="center">
                                                <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                                       role="presentation">
                                                    <tr>
                                                        <td align="center">
                                                            {{$data['message']}}<br>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <table class="action" align="center" width="100%" cellpadding="0"
                                                       cellspacing="0" role="presentation">
                                                    <tr>
                                                        <td>
                                                            <table width="100%" border="0" cellpadding="0"
                                                                   cellspacing="0" role="presentation">
                                                                <tr>
                                                                    <td>
                                                                        <table align="center" border="0" cellpadding="0"
                                                                               cellspacing="0" role="presentation">
                                                                            <tr>
                                                                                <td>
                                                                                    <a href="{{ $data['verificationUrl'] }}"
                                                                                       class="button button-{{ $color ?? 'primary' }}"
                                                                                       target="_blank"
                                                                                       rel="noopener">{{ $data['verificationBtn'] }}</a>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <table class="action" width="100%" cellpadding="0" cellspacing="0"
                                                       role="presentation">
                                                    <tr>
                                                        <td>
                                                            Если по каким либо причинам у вас не отображается кнопка,
                                                            или вы не можете нажать на нее, перейдите по ссылке ниже в
                                                            вашем браузере.
                                                            <a href="{{$data['verificationUrl']}}">{{$data['verificationUrl']}}</a>
                                                        </td>
                                                    </tr>
                                                    <tr style="margin-top: 40px">
                                                        <td><br><br><br>
                                                            C уважением, команда {{ config('app.name') }}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0"
                                           role="presentation">
                                        <tr>
                                            <td class="content-cell" align="center">
                                                © {{ date('Y') }} {{ config('app.name') }}. Все права защищены.
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
</body>
</html>
