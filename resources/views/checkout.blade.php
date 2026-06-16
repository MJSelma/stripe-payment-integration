<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment</title>
    <!-- Include Tailwind CSS for quick styling -->
    <script src="https://jsdelivr.net"></script>
    <!-- Include Stripe.js (Mandatory) -->
    <script src="https://stripe.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Complete Your Payment</h2>
        
        <!-- Payment Form -->
        <form id="payment-form">
            <!-- Stripe Element will be injected here -->
            <div id="payment-element" class="mb-4"></div>

            <!-- Error message container -->
            <div id="error-message" class="text-red-500 text-sm mb-4 hidden"></div>

            <!-- Submit Button -->
            <button id="submit-btn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 disabled:opacity-50">
                <span id="button-text">Pay Now ($20.00)</span>
                <span id="spinner" class="hidden">Processing...</span>
            </button>
        </form>
    </div>

    <script>
        // 1. Initialize Stripe
        const stripe = Stripe("{{ $stripeKey }}");
        
        // 2. Initialize Elements with the Client Secret from backend
        const options = { clientSecret: "{{ $clientSecret }}" };
        const elements = stripe.elements(options);

        // 3. Create and mount the Payment Element (handles cards, wallets, etc.)
        const paymentElement = elements.create('payment');
        paymentElement.mount('#payment-element');

        // 4. Handle form submission
        const form = document.getElementById('payment-form');
        const submitBtn = document.getElementById('submit-btn');
        const errorDiv = document.getElementById('error-message');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            
            // Disable button to prevent double-clicks
            submitBtn.disabled = true;
            document.getElementById('button-text').classList.add('hidden');
            document.getElementById('spinner').classList.remove('hidden');
            errorDiv.classList.add('hidden');

            // 5. Confirm payment with Stripe
            const { error } = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    // Redirect URL after successful payment authentication
                    return_url: "{{ route('payment.success') }}",
                },
            });

            // This section only runs if there is an immediate error (e.g., card declined)
            if (error) {
                errorDiv.textContent = error.message;
                errorDiv.classList.remove('hidden');
                
                // Re-enable button
                submitBtn.disabled = false;
                document.getElementById('button-text').classList.remove('hidden');
                document.getElementById('spinner').classList.add('hidden');
            }
        });
    </script>
</body>
</html>
