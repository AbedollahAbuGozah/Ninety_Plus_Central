<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="edge"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet"/>
</head>
<body style="margin: 0; font-family: 'Poppins', sans-serif; background: #ffffff; font-size: 14px;">
<div
    style="max-width: 680px; margin: 0 auto; padding: 45px 30px 60px; background: #f4f7ff; background-image: url({{asset('images/students.jpeg')}}); background-repeat: no-repeat; background-size: 800px 452px; background-position: top center; font-size: 14px; color: #434343;">
    <header>
        <table style="width: 100%;">
            <tbody>
            <tr style="height: 0;">
                <td>
                    <img alt="ninetyPlus" style="border-radius: 10px" src="{{ asset('logos/ninetyPlus.jpg') }}" height="30px"/>
                </td>
                <td style="text-align: right;">
                    <span
                        style="font-size: 16px; line-height: 30px; color: #ffffff;"> {{\Carbon\Carbon::now()->format('d M g A')}} </span>
                </td>
            </tr>
            </tbody>
        </table>
    </header>
    <main>
        <div
            style="margin: 0; margin-top: 70px; padding: 92px 30px 115px; background: #ffffff; border-radius: 30px; text-align: center;">
            <div style="width: 100%; max-width: 489px; margin: 0 auto;">
                <img style="margin: 0 auto; padding: 0; border-radius: 10px" alt="ninetyPlus" src="{{ asset('logos/ninetyPlus.jpg') }}" height="120px"/>
                <h1 style="margin: 0; font-size: 24px; font-weight: 500; color: #1f1f1f;">
                    {{'Ninety Plus'}}
                </h1>
                <p style="margin: 0; margin-top: 17px; font-size: 16px; font-weight: 500;">
                    Hey {{ $notifiable->first_name ?? ''}}
                </p>
                <p style="margin: 0; margin-top: 17px; font-weight: 500; letter-spacing: 0.56px;">
                    {!! $content ?? '' !!}
                </p>
                @if(isset($hint))
                    <p style="margin: 0; margin-top: 60px; font-size: 40px; font-weight: 600; letter-spacing: 25px; color: #ba3d4f;">
                        {!! $hint ?? '' !!}
                    </p>
                @endif
                <p style="margin: 0; padding: 3rem">
                    @if(isset($actionUrl))
                        <a href="{{$actionUrl}}" style="display: inline-block; padding: 10px 20px; font-size: 16px; color: #fff; background-color: orange; text-decoration: none; border-radius: 5px;">Verify your account</a>
                    @else
                        <span style="letter-spacing: 10px">
                            $code
                        </span>
                    @endif
                </p>
            </div>
        </div>
    </main>
    <footer
        style="width: 100%; max-width: 490px; margin: 20px auto 0; text-align: center; border-top: 1px solid #e6ebf1;">
        <p style="margin: 0; margin-top: 40px; font-size: 16px; font-weight: 600; color: #434343;">
            Smart Platform For Modern Education
        </p>
        <p style="margin: 0; margin-top: 8px; color: #434343;">
            Palestine.
        </p>
        <div style="margin: 0; margin-top: 16px;">
            <a href="#" target="_blank" style="display: inline-block;">
                <img width="36px" alt="Facebook"
                     src="https://archisketch-resources.s3.ap-northeast-2.amazonaws.com/vrstyler/1661502815169_682499/email-template-icon-facebook"/>
            </a>
            <a href="#" target="_blank"
               style="display: inline-block; margin-left: 8px;">
                <img width="36px" alt="Instagram"
                     src="https://archisketch-resources.s3.ap-northeast-2.amazonaws.com/vrstyler/1661504218208_684135/email-template-icon-instagram"/>
            </a>
            <a href="#" target="_blank"
               style="display: inline-block; margin-left: 8px;">
                <img width="36px" alt="Youtube"
                     src="https://archisketch-resources.s3.ap-northeast-2.amazonaws.com/vrstyler/1661503195931_210869/email-template-icon-youtube"/>
            </a>
        </div>
        <p style="margin: 0; margin-top: 16px; color: #434343;">
            Copyright © 2024 Company. All rights reserved.
        </p>
    </footer>
</div>
</body>
</html>
