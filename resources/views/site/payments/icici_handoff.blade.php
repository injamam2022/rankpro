<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Confirming payment</title>
    <meta http-equiv="refresh" content="0;url={{ $url }}">
</head>
<body>
    <p>Confirming your payment…</p>
    <script>window.location.replace(@json($url));</script>
</body>
</html>
