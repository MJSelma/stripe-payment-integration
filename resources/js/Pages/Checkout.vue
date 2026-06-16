<template>
    <div class="bg-slate-50 min-h-screen font-sans text-slate-800 antialiased">
      <!-- Clean, minimalist header navigation bar -->
      <header class="bg-white border-b border-slate-200 py-4 px-6 mb-8 lg:mb-12">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
          <div class="flex items-center gap-2 font-bold text-lg tracking-tight text-slate-900">
            <span class="w-6 h-6 bg-blue-600 rounded-md flex items-center justify-center text-white text-xs">W4</span>
            Work4U Platform
          </div>
          <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full border border-slate-200">
            🔒 Secure Checkout
          </span>
        </div>
      </header>
  
      <!-- Interactive two-column dashboard layout grid -->
      <main class="max-w-5xl mx-auto px-4 pb-16 grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
        
        <!-- LEFT COLUMN: Payment Forms Block (7 Columns Wide) -->
        <section class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm h-fit">
          <h2 class="text-xl font-bold text-slate-900 mb-1">Payment Details</h2>
          <p class="text-sm text-slate-500 mb-6">Complete your transaction securely via encrypted payment lines.</p>
          
          <form @submit.prevent="handleSubmit" class="space-y-6">
            <!-- The container Stripe uses to dynamically render the fields -->
            <div id="payment-element"></div>
  
            <!-- Dynamic error alerting banner box -->
            <div v-if="errorMessage" class="text-rose-600 text-sm bg-rose-50 border border-rose-200 p-4 rounded-xl flex gap-2 items-start">
              <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
              </svg>
              <span>{{ errorMessage }}</span>
            </div>
  
            <!-- Premium CTA submit option -->
            <button 
              :disabled="isProcessing" 
              class="w-full bg-slate-900 hover:bg-black text-white font-semibold py-3.5 px-4 rounded-xl transition duration-200 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 text-sm"
            >
              <span v-if="!isProcessing">Pay $20.00 USD</span>
              <span v-else class="flex items-center gap-2">
                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                Processing payment securely...
              </span>
            </button>
          </form>
        </section>
  
        <!-- RIGHT COLUMN: Professional Invoice Summary Card (5 Columns Wide) -->
        <section class="lg:col-span-5 space-y-4">
          <div class="bg-slate-100/80 rounded-2xl p-6 border border-slate-200">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Order Summary</h3>
            
            <!-- Sample Line Item block row -->
            <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-200/60">
              <div>
                <p class="font-semibold text-sm text-slate-900">Standard Access License</p>
                <p class="text-xs text-slate-500">1x Account Membership Access</p>
              </div>
              <span class="font-semibold text-sm text-slate-900">$20.00</span>
            </div>
  
            <!-- Totals Area breakdown list -->
            <div class="space-y-2 pt-4 text-sm">
              <div class="flex justify-between text-slate-600">
                <span>Subtotal</span>
                <span>$20.00</span>
              </div>
              <div class="flex justify-between text-slate-600">
                <span>Processing Fees</span>
                <span class="text-green-600 font-medium">Free</span>
              </div>
              <div class="flex justify-between font-bold text-base text-slate-900 pt-2 border-t border-slate-200">
                <span>Total Due</span>
                <span>$20.00 USD</span>
              </div>
            </div>
          </div>
  
          <p class="text-center text-xs text-slate-400 px-4">
            By completing this transaction, you agree to our terms of service and license guarantees.
          </p>
        </section>
  
      </main>
    </div>
  </template>
  
  <script setup>
  import { onMounted, ref } from 'vue';
  import { loadStripe } from '@stripe/stripe-js';
  
  const props = defineProps({
    stripeKey: String,
    clientSecret: String,
  });
  
  const isProcessing = ref(false);
  const errorMessage = ref('');
  let stripe = null;
  let elements = null;
  
  onMounted(async () => {
    stripe = await loadStripe(props.stripeKey);
    
    // Customizing Stripe variables to look beautiful and minimalist
    const appearance = {
      theme: 'flat', // 'flat' gives a clean modern layout with subtle flat lines
      variables: {
        fontFamily: 'Instrument Sans, system-ui, sans-serif',
        colorPrimary: '#0f172a',     // Slate-900 theme highlighting profile
        colorBackground: '#ffffff',  // Crisp white inner container backgrounds
        colorText: '#1e293b',        // Dark grey font colors
        colorDanger: '#e11d48',      // Vivid rose error labels
        borderRadius: '12px',        // Smooth modern corners matching Tailwind's rounded-2xl
        spacingUnit: '5px',          // Comfortable form paddings
      },
      rules: {
        '.Input': {
          border: '1px solid #cbd5e1', // Explicit Slate-300 borders
          boxShadow: '0 1px 2px 0 rgba(0, 0, 0, 0.05)', // Drop subtle elevation shadows
          transition: 'border-color 0.15s ease, box-shadow 0.15s ease',
        },
        '.Input:focus': {
          border: '1px solid #3b82f6', // Sharp focus border highlight colors
          boxShadow: '0 0 0 3px rgba(59, 130, 246, 0.15)',
        },
        '.Label': {
          fontWeight: '500',
          fontSize: '13px',
          color: '#475569',
        }
      }
    };
  
    elements = stripe.elements({ 
      clientSecret: props.clientSecret,
      appearance: appearance 
    });
    
    // The layout variant 'tabs' creates beautiful credit card toggle structures
    const paymentElement = elements.create('payment', {
      layout: 'tabs'
    });
    paymentElement.mount('#payment-element');
  });
  
  const handleSubmit = async () => {
    if (!stripe || !elements) return;
  
    isProcessing.value = true;
    errorMessage.value = '';
  
    const { error } = await stripe.confirmPayment({
      elements,
      confirmParams: {
        return_url: window.location.origin + '/payment-success', 
      },
    });
  
    if (error) {
      errorMessage.value = error.message;
      isProcessing.value = false;
    }
  };
  </script>
  