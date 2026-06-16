<template>
    <div class="bg-gray-100 flex items-center justify-center min-h-screen font-sans">
      <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-md text-center">
        <!-- Green Success Icon Badge -->
        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
        </div>
        
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Thank You!</h2>
        <p class="text-gray-600 mb-6">Your payment was processed successfully. A confirmation email has been sent.</p>
        
        <!-- Redirect Home Button Link -->
        <a href="/" class="inline-block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
          Return to Dashboard
        </a>
      </div>
    </div>
  </template>
  