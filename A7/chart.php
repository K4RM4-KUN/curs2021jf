<?php //Preguntar si es mejor pasar los datos por el session o consultar la DB dos veces
    session_start();
    include 'library.php';
    include 'libraryShop.php';
    controlLogedPrivate();
    userInfo();
    buttonGet();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Buy cool new product</title>
        <link rel="stylesheet" href="style.css">
        <script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
        <script src="https://js.stripe.com/v3/"></script>
    </head>
<body>
    <section>
        <div class="product">
            <a href="loged.php"><button type="button">Atras</button></a>
            <h1 style="margin-top:50px; text-align: center;">TU CARRITO</h1>
            <center><?php
                if(isset($_SESSION["chart"]) && !empty($_SESSION["chart"])){
                    echo '<table border>
                        <tr>';
                            writeChart();
                    echo '</tr>
                    </table>';
                } else {
                  echo "No hay objetos en tu carrito!";
                }
                ?>
            </center>
        </div>
    </section>
</body>

    <script type="text/javascript">
    // Create an instance of the Stripe object with your publishable API key
    var stripe = Stripe("pk_test_51HosN6JM40zfaHAMGYoHx2h5xWvbyXJAa9egn9jgWq2imsMdt1OOGE7OK99z1RR9mr0oo4cQau3fCu0lOzUPRnHi00VnEHSmp0");
    var checkoutButton = document.getElementById("checkout-button");
    checkoutButton.addEventListener("click", function () {
      fetch("./create-session.php", {
        method: "POST",
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (session) {
          return stripe.redirectToCheckout({ sessionId: session.id });
        })
        .then(function (result) {
          // If redirectToCheckout fails due to a browser or network
          // error, you should display the localized error message to your
          // customer using error.message.
          if (result.error) {
            alert(result.error.message);
          }
        })
        .catch(function (error) {
          console.error("Error:", error);
        });
    });
  </script>

</html>