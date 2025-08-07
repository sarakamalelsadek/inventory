<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Low Stock Alert</title>
</head>
<body>
    <h2>Low Stock Alert</h2>

    <p>items: {{ $stock->item->name }}</p>
    <p>current quantity: {{ $stock->quantity }}</p>
    <p>warehouse: {{ $stock->warehouse->name }}</p>

    <p>Please resupply as soon as possible.</p>
</body>
</html>