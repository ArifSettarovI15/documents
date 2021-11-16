<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TestMail</title>
</head>
<body>
<h3>Необходимо проверить оплату следующих счетов:</h3>
<ul>
    @foreach($details->invoices as $invoice)
        <li>{{$invoice->invoice_name}}</li>
    @endforeach
</ul>

</body>
</html>
