<template>
    <div class="payment-history-body">
      <div class="container">
        
        <!-- Top Bar -->
        <header class="topbar">
          <div class="brand">
            <div class="brand-mark">W4</div>
            <div>
              Work4U Tutor
              <small>Billing Control Panel</small>
            </div>
          </div>
          <nav class="nav">
            <a href="/checkout">Checkout Form</a>
            <a href="#" class="active">History Log</a>
          </nav>
          <div class="avatar">MJS</div>
        </header>
  
        <!-- Page Header Section -->
        <section class="page-header">
          <div class="page-title">
            <span class="eyebrow">Financial Dashboard</span>
            <h1>Statements &amp; <em>Ledger</em></h1>
            <p>Review current operational accounting logs, pending session balances, and historical invoices.</p>
            
            <!-- Tab Toggles -->
            <div class="page-tabs">
              <button 
                @click="activeTab = 'pending'" 
                :class="['page-tab', { active: activeTab === 'pending' }]"
                type="button"
              >
                <span class="page-tab-dot"></span>
                Pending Settlements ({{ pendingPayments ? pendingPayments.length : 0 }})
              </button>
              <button 
                @click="activeTab = 'previous'" 
                :class="['page-tab', { active: activeTab === 'previous' }]"
                type="button"
              >
                <span class="page-tab-dot"></span>
                Previous Records ({{ previousPayments ? previousPayments.length : 0 }})
              </button>
            </div>
          </div>
  
          <button class="add-account" type="button">
            <span class="plus">+</span> Export Audit File
          </button>
        </section>
  
        <!-- Summary Metrics Grid -->
        <section :class="['summary', activeTab === 'pending' ? 'summary-three' : '']">
          <div class="summary-cell featured">
            <h3>Total Balance Value</h3>
            <div class="price-val">\$130.00 USD</div>
          </div>
          <div class="summary-cell">
            <h3>Active Statements</h3>
            <div class="metric-val">{{ (pendingPayments ? pendingPayments.length : 0) + (previousPayments ? previousPayments.length : 0) }}</div>
          </div>
          <div class="summary-cell">
            <h3>Settled Accounts</h3>
            <div class="metric-val">2 Invoices</div>
          </div>
        </section>
  
        <!-- DATA VIEWS TABLE -->
        <main>
          <!-- Pending Tab Panel -->
          <div v-if="activeTab === 'pending'" class="view active">
            <div class="table-card">
              <table class="history-table">
                <thead>
                  <tr>
                    <th>Transaction ID</th>
                    <th>Date Issued</th>
                    <th>Service Description</th>
                    <th>Amount</th>
                    <th class="text-right">Status State</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in pendingPayments" :key="item.id">
                    <td><code class="txn-code">{{ item.id }}</code></td>
                    <td>{{ item.date }}</td>
                    <td class="font-medium text-ink">{{ item.description }}</td>
                    <td class="font-bold text-ink">\${{ item.amount }}</td>
                    <td class="text-right">
                      <span class="status-badge status-pending">
                        <span class="dot"></span> Pending
                      </span>
                    </td>
                  </tr>
                  <tr v-if="!pendingPayments || pendingPayments.length === 0">
                    <td colspan="5" class="empty-state">No pending transactions recorded.</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
  
          <!-- Previous Tab Panel -->
          <div v-if="activeTab === 'previous'" class="view active">
            <div class="table-card">
              <table class="history-table">
                <thead>
                  <tr>
                    <th>Transaction ID</th>
                    <th>Date Settled</th>
                    <th>Service Description</th>
                    <th>Amount</th>
                    <th class="text-right">Status State</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in previousPayments" :key="item.id">
                    <td><code class="txn-code">{{ item.id }}</code></td>
                    <td>{{ item.date }}</td>
                    <td class="font-medium text-ink">{{ item.description }}</td>
                    <td class="font-bold text-ink">\${{ item.amount }}</td>
                    <td class="text-right">
                      <span :class="['status-badge', item.status === 'paid' ? 'status-paid' : 'status-claimed']">
                        <span class="dot"></span> 
                        {{ item.status === 'paid' ? 'Paid Secure' : 'Claimed' }}
                      </span>
                    </td>
                  </tr>
                  <tr v-if="!previousPayments || previousPayments.length === 0">
                    <td colspan="5" class="empty-state">No previous transactions recorded.</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </main>
  
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  defineProps({
    pendingPayments: {
      type: Array,
      default: () => []
    },
    previousPayments: {
      type: Array,
      default: () => []
    }
  });
  
  const activeTab = ref('pending');
  </script>
  
  <style scoped>
  .payment-history-body {
    --blue-pale: #C8E4E5;
    --blue-pale-soft: #E8F3F4;
    --blue-mid: #4A9DA8;
    --blue-deep: #2D5A66;
    --blue-deep-hover: #234852;
    --white: #FFFFFF;
    --bg-soft: #FAFBFC;
    --line: #EAEEF0;
    --ink: #1A2B33;
    --ink-soft: #4A5B63;
    --ink-muted: #8896A0;
    --ink-faint: #B4BEC4;
    
    --green-bg: #DCEFD8;
    --green-text: #3D7A3F;
    --green-dot: #5BA84F;
    --orange-bg: #FCE4CC;
    --orange-text: #A35B1A;
    --orange-dot: #E89548;
    --claimed-bg: #E8F3F4;
    --claimed-text: #2D5A66;
    --claimed-dot: #4A9DA8;
  
    background: var(--white);
    color: var(--ink);
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
  }
  
  .container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 36px 40px 80px;
  }
  
  .topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 24px;
    border-bottom: 1px solid var(--line);
    margin-bottom: 44px;
  }
  .brand {
    display: flex;
    align-items: center;
    gap: 12px;
    font-family: 'Nunito', sans-serif;
    font-weight: 800;
    font-size: 18px;
    color: var(--blue-deep);
  }
  .brand-mark {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--blue-mid) 0%, var(--blue-deep) 100%);
    display: grid;
    place-items: center;
    color: var(--white);
  }
  .brand small {
    display: block;
    font-family: 'Inter', sans-serif;
    font-style: italic;
    font-weight: 400;
    font-size: 11px;
    color: var(--ink-muted);
    margin-top: 3px;
  }
  .nav {
    display: flex;
    gap: 2px;
    font-size: 13px;
    background: var(--bg-soft);
    padding: 4px;
    border-radius: 10px;
    border: 1px solid var(--line);
  }
  .nav a {
    padding: 8px 16px;
    color: var(--ink-soft);
    text-decoration: none;
    border-radius: 7px;
    font-weight: 500;
  }
  .nav a.active {
    background: var(--white);
    color: var(--blue-deep);
    font-weight: 600;
    box-shadow: 0 1px 2px rgba(45, 90, 102, 0.04);
  }
  .avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: var(--blue-pale);
    color: var(--blue-deep);
    display: grid;
    place-items: center;
    font-family: 'Nunito', sans-serif;
    font-weight: 700;
    border: 1px solid var(--line);
  }
  
  .page-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 32px;
    margin-bottom: 36px;
  }
  .page-title .eyebrow {
    display: inline-block;
    font-style: italic;
    font-size: 13px;
    color: var(--blue-mid);
    font-weight: 500;
    margin-bottom: 10px;
  }
  .page-title h1 {
    font-family: 'Nunito', sans-serif;
    font-weight: 800;
    font-size: 36px;
    color: var(--ink);
  }
  .page-title h1 em {
    font-style: italic;
    font-weight: 500;
    color: var(--blue-mid);
  }
  .page-title p {
    margin-top: 10px;
    color: var(--ink-soft);
    max-width: 480px;
  }
  
  .page-tabs {
    display: inline-flex;
    gap: 8px;
    margin-top: 24px;
    background: var(--bg-soft);
    padding: 5px;
    border-radius: 12px;
    border: 1px solid var(--line);
  }
  .page-tab {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 18px;
    font-family: 'Nunito', sans-serif;
    font-size: 13.5px;
    font-weight: 700;
    color: var(--ink-soft);
    background: transparent;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  .page-tab-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--ink-faint);
  }
  .page-tab.active {
    background: var(--blue-deep);
    color: var(--white);
  }
  .page-tab.active .page-tab-dot {
    background: var(--blue-pale);
  }
  
  .add-account {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 13px 22px;
    background: var(--blue-deep);
    color: var(--white);
    border: none;
    border-radius: 10px;
    font-family: 'Nunito', sans-serif;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  .add-account:hover { background: var(--blue-deep-hover); }
  .add-account .plus {
    width: 22px;
    height: 22px;
    border-radius: 6px;
    background: var(--blue-mid);
    display: grid;
    place-items: center;
  }
  
  .summary {
    display: grid;
    grid-template-columns: 1.3fr 1fr 1fr 1fr;
    gap: 16px;
    margin-bottom: 44px;
  }
  .summary.summary-three {
    grid-template-columns: 1.3fr 1fr 1fr;
  }
  .summary-cell {
    background: var(--white);
    border: 1px solid var(--line);
    border-radius: 14px;
    padding: 22px 24px;
  }
  .summary-cell h3 {
    font-size: 13px;
    color: var(--ink-soft);
    margin-bottom: 6px;
  }
  .summary-cell.featured {
    background: linear-gradient(135deg, var(--blue-deep) 0%, var(--blue-mid) 100%);color: var(--white);}.summary-cell.featured h3 { color: var(--blue-pale); }.price-val { font-size: 24px; font-weight: 800; font-family: 'Nunito'; }.metric-val { font-size: 24px; font-weight: 700; color: var(--ink); font-family: 'Nunito'; }.table-card {border: 1px solid var(--line);border-radius: 14px;overflow: hidden;background: var(--white);}.history-table {width: 100%;border-collapse: collapse;text-align: left;}.history-table th {background: var(--bg-soft);padding: 14px 20px;font-size: 12px;text-transform: uppercase;letter-spacing: 0.05em;color: var(--ink-soft);border-bottom: 1px solid var(--line);}.history-table td {padding: 16px 20px;border-bottom: 1px solid var(--line);color: var(--ink-soft);}.history-table tr:last-child td { border-bottom: none; }.txn-code {font-family: monospace;background: var(--bg-soft);padding: 3px 6px;border-radius: 4px;color: var(--blue-deep);}.text-right { text-align: right; }.font-medium { font-weight: 500; }.font-bold { font-weight: 700; }.text-ink { color: var(--ink); }.status-badge {display: inline-flex;align-items: center;gap: 6px;padding: 4px 10px;border-radius: 20px;font-size: 12px;font-weight: 600;}.status-badge .dot { width: 6px; height: 6px; border-radius: 50%; }.status-pending { background: var(--orange-bg); color: var(--orange-text); }.status-pending .dot { background: var(--orange-dot); }.status-paid { background: var(--green-bg); color: var(--green-text); }.status-paid .dot { background: var(--green-dot); }.status-claimed { background: var(--claimed-bg); color: var(--claimed-text); border: 1px solid rgba(74, 157, 168, 0.2); }.status-claimed .dot { background: var(--claimed-dot); }.empty-state { text-align: center; color: var(--ink-muted); padding: 40px !important; } </style>  