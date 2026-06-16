<script setup>
import { ref } from 'vue'

const activeTab = ref('pending')
</script>

<template>
  <div class="min-h-screen bg-white p-8">

    <!-- HEADER -->
    <div class="flex justify-between items-end mb-8">
      <div>
        <p class="text-sm italic text-slate-500">Finances · 2026</p>

        <h1 class="text-4xl font-bold">
          Payment <span class="italic text-blue-600">history</span>
        </h1>

        <!-- Tabs -->
        <div class="flex gap-2 mt-4 bg-slate-100 p-1 rounded-xl w-fit">
          <button
            @click="activeTab = 'pending'"
            :class="activeTab === 'pending'
              ? 'bg-white shadow px-4 py-2 rounded-lg font-semibold'
              : 'px-4 py-2 text-slate-500'"
          >
            Pending Payments
          </button>

          <button
            @click="activeTab = 'previous'"
            :class="activeTab === 'previous'
              ? 'bg-white shadow px-4 py-2 rounded-lg font-semibold'
              : 'px-4 py-2 text-slate-500'"
          >
            Previous Payments
          </button>
        </div>
      </div>

      <button class="bg-blue-600 text-white px-5 py-3 rounded-xl font-semibold">
        + Add account
      </button>
    </div>

    <!-- ================= PENDING ================= -->
    <div v-if="activeTab === 'pending'">

      <!-- SUMMARY -->
      <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="p-5 border rounded-xl">
          <p class="text-sm text-slate-500">Available to claim</p>
          <p class="text-2xl font-bold">$1,248.00</p>
        </div>

        <div class="p-5 border rounded-xl">
          <p class="text-sm text-slate-500">To claim</p>
          <p class="text-2xl font-bold">$340.00</p>
        </div>

        <div class="p-5 border rounded-xl">
          <p class="text-sm text-slate-500">Monthly earnings</p>
          <p class="text-2xl font-bold">$1,588.00</p>
        </div>
      </div>

      <!-- LIST -->
      <div class="space-y-4">

        <div class="border rounded-xl p-5 flex justify-between">
          <div>
            <p class="font-bold">CLS-20418</p>
            <p class="text-sm text-slate-500">Amelia Brennan</p>
            <p class="text-sm">Organic Chemistry</p>
          </div>

          <div class="text-right">
            <p class="font-bold text-lg">$85.00</p>
            <p class="text-green-600 text-sm">Claimable</p>
          </div>
        </div>

        <div class="border rounded-xl p-5 flex justify-between">
          <div>
            <p class="font-bold">CLS-20417</p>
            <p class="text-sm text-slate-500">Theodore Okafor</p>
            <p class="text-sm">Linear Algebra</p>
          </div>

          <div class="text-right">
            <p class="font-bold text-lg">$72.50</p>
            <p class="text-green-600 text-sm">Claimable</p>
          </div>
        </div>

      </div>
    </div>

    <!-- ================= PREVIOUS ================= -->
    <div v-else>

      <!-- EMPTY STATE -->
      <div class="border border-dashed rounded-xl p-10 text-center text-slate-500">
        <p class="text-lg font-semibold">Select a period to begin</p>
        <p class="text-sm mt-2">
          Choose a month and year to view previous payments
        </p>
      </div>

    </div>

  </div>
</template>