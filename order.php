<?php
require_once("includes/db_connect.php");
include("templates/nav.php");

$result = $conn->query("SELECT * FROM food");
?>

<div class="container">
    <h2 style="text-align:center; color:#d9232d;">Place Your KFC-Inspired Order</h2>

    <form id="orderForm">
        <label for="foodId">Select Food</label>
        <select name="foodId" id="foodId" required>
            <?php while ($food = $result->fetch_assoc()): ?>
                <option value="<?= $food['foodId'] ?>" data-price="<?= $food['price'] ?>">
                    <?= htmlspecialchars($food['name']) ?> - KSh <?= number_format($food['price'], 2) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="customerName">Your Name</label>
        <input type="text" name="customerName" id="customerName" required>

        <label for="phoneNumber">Phone Number</label>
        <input type="text" name="phoneNumber" id="phoneNumber" placeholder="07XXXXXXXX" required>

        <label for="quantity">Quantity</label>
        <input type="number" name="quantity" id="quantity" min="1" value="1" required>

        <button type="submit" id="submitBtn">Place Order</button>
        <div id="paymentMessage" style="margin-top: 10px; color: green;"></div>
    </form>
</div>

<script>
document.getElementById("orderForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const foodId = document.getElementById("foodId").value;
    const foodPrice = document.querySelector(`#foodId option[value="${foodId}"]`).dataset.price;
    const quantity = document.getElementById("quantity").value;
    const totalAmount = foodPrice * quantity;
    const phone = document.getElementById("phoneNumber").value;
    const name = document.getElementById("customerName").value;

    // Submit order to server
    fetch("process_order.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `foodId=${foodId}&customerName=${name}&phoneNumber=${phone}&quantity=${quantity}`
    })
    .then(res => res.json())
    .then(orderResponse => {
        if (orderResponse.success) {
            // Trigger STK push
            return fetch("mpesa/stk_push.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `amount=${totalAmount}&phone_number=254${phone.substring(1)}`
            });
        } else {
            throw new Error("Order failed: " + orderResponse.message);
        }
    })
    .then(response => response.json())
    .then(stkResponse => {
        if (stkResponse.ResponseCode === "0") {
            document.getElementById("paymentMessage").textContent =
                "STK push sent! Please complete payment on your phone.";
        } else {
            document.getElementById("paymentMessage").textContent =
                "Failed to initiate payment: " + (stkResponse.errorMessage || "Unknown error.");
        }
    })
    .catch(err => {
        document.getElementById("paymentMessage").textContent = "Error: " + err.message;
    });
});
</script>

<?php include("templates/footer.php"); ?>
