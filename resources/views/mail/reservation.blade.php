<!DOCTYPE html>
<html>
<head>
    <title>Reservation Form Submission</title>
</head>
<body>
    <h2>New Reservation Form Submission</h2>
    <p><strong>Name: </strong> {{ $details['name'] }}</p>
    <p><strong>Mobile: </strong> {{ $details['mobile'] }}</p>
    <p><strong>Email: </strong> {{ $details['email'] }}</p>
    <p><strong>Check-In: </strong> {{ $details['checkin'] }}</p>
    <p><strong>Check-Out: </strong> {{ $details['checkout'] }}</p>
    <p><strong>Rooms: </strong> {{ $details['rooms'] }}</p>
    <p><strong>Guests: </strong> {{ $details['guests'] }}</p>
    <p><strong>Room Type: </strong> {{ $details['roomType'] }}</p>
</body>
</html>
