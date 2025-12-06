<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
</head>
<body class="container mt-5">

<h3 class="mb-4 text-center">Payment Form</h3>

<form action="request.php" method="post" class="mx-auto col-md-6">
    <div class="mb-3">
        <label>Amount:</label>
        <select name="amount" class="form-control" required>
            <option value="1000">1,000</option>
            <option value="5000">5,000</option>
            <option value="10000">10,000</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Description:</label>
        <input type="text" name="description" class="form-control" required>
        <!-- <select name="description" class="form-control" required>
            <option value="Test Purchase 1">Test Purchase 1</option>
            <option value="Test Purchase 2">Test Purchase 2</option>
            <option value="Test Purchase 3">Test Purchase 3</option>
        </select> -->
    </div>

    <div class="mb-3">
        <label>Email:</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Mobile:</label>
        <input type="text" name="mobile" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">Pay Now</button>
</form>

</body>
</html>
