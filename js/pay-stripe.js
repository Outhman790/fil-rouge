// const stripe = Stripe(
//   "pk_test_51NHQn6JsAbiLXvIKNjjFfgX2DN7REddbYH4aCmsTqOBxkAaiY6aN3VKaPSFqaseRAWrtQmddQXfqeazLQMhz10mV00koniZEvH"
// );
// const elements = stripe.elements();
// const cardElement = elements.create("card");

// cardElement.mount("#card-element");

// const form = document.getElementById("payment-form");
// const errorElement = document.getElementById("card-errors");

// form.addEventListener("submit", async (event) => {
//   event.preventDefault();

//   const { paymentMethod, error } = await stripe.createPaymentMethod({
//     type: "card",
//     card: cardElement,
//   });

//   if (error) {
//     errorElement.textContent = error.message;
//   } else {
//     const paymentAmount = 30000; // Replace with the actual payment amount
//     const customerName = "John Doe"; // Replace with the customer name
//     const customerEmail = "john@example.com"; // Replace with the customer email

//     const formData = new FormData();
//     formData.append("paymentAmount", paymentAmount);
//     formData.append("customerName", customerName);
//     formData.append("customerEmail", customerEmail);
//     formData.append("paymentMethodId", paymentMethod.id);

//     const response = await fetch("./pay-stripe.php", {
//       method: "POST",
//       body: formData,
//     });

//     const data = await response.json();

//     if (data.error) {
//       errorElement.textContent = data.error;
//     } else {
//       // Handle successful payment
//       alert("Payment successful!");
//     }
//   }
// });
const stripe = Stripe(
  "pk_test_51NHQn6JsAbiLXvIKNjjFfgX2DN7REddbYH4aCmsTqOBxkAaiY6aN3VKaPSFqaseRAWrtQmddQXfqeazLQMhz10mV00koniZEvH"
);
var checkoutButton = document.getElementById("checkout-button");

checkoutButton.addEventListener("click", function () {
  fetch("create-checkout-session.php", {
    method: "POST",
  })
    .then(function (response) {
      return response.json();
    })
    .then(function (session) {
      return stripe.redirectToCheckout({ sessionId: session.id });
    })
    .then(function (result) {
      if (result.error) {
        alert(result.error.message);
      }
    })
    .catch(function (error) {
      console.error("Error:", error);
    });
});
