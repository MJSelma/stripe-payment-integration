import React, { useEffect, useState, useRef } from 'react';
import { loadStripe } from '@stripe/stripe-js';

export default function Checkout({ stripeKey, clientSecret }) {
  const [isProcessing, setIsProcessing] = useState(false);
  const [errorMessage, setErrorMessage] = useState('');
  const stripeRef = useRef(null);
  const elementsRef = useRef(null);

  useEffect(() => {
    async function initStripe() {
      // 1. Load Stripe safely
      stripeRef.current = await loadStripe(stripeKey);
      
      // 2. Create and mount Payment Element
      elementsRef.current = stripeRef.current.elements({ clientSecret });
      const paymentElement = elementsRef.current.create('payment');
      paymentElement.mount('#payment-element');
    }
    initStripe();
  }, [stripeKey, clientSecret]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!stripeRef.current || !elementsRef.current) return;

    setIsProcessing(true);
    setErrorMessage('');

    // 3. Submit payment details
    const { error } = await stripeRef.current.confirmPayment({
      elements: elementsRef.current,
      confirmParams: {
        return_url: window.location.origin + '/payment-success',
      },
    });

    if (error) {
      setErrorMessage(error.message);
      setIsProcessing(false);
    }
  };

  return (
    <div class="bg-gray-100 flex items-center justify-center min-h-screen">
      <div className="w-full max-w-md bg-white p-8 rounded-xl shadow-md">
        <h2 className="text-2xl font-bold mb-6 text-gray-800">Complete Your Payment</h2>
        
        <form onSubmit={handleSubmit}>
          <div id="payment-element" className="mb-4"></div>

          {errorMessage && (
            <div className="text-red-500 text-sm mb-4">{errorMessage}</div>
          )}

          <button 
            disabled={isProcessing} 
            className="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 disabled:opacity-50"
          >
            {isProcessing ? 'Processing...' : 'Pay Now ($20.00)'}
          </button>
        </form>
      </div>
    </div>
  );
}
