<template>
    <div :class="['payment-history-body', { 'modal-open': isModalOpen }]">
      <div class="container">
        
        <!-- Top Bar Navbar -->
        <header class="topbar">
          <div class="brand">
            <div class="brand-mark">W4</div>
            <div>
              Work4U Tutor
              <small>Tutor Earnings Panel</small>
            </div>
          </div>
          <nav class="nav">
            <a href="/payment-history">Student Ledger</a>
            <a href="#" class="active">Disbursements</a>
          </nav>
          <div class="avatar">TTR</div>
        </header>
  
        <!-- Page Header -->
        <section class="page-header">
          <div class="page-title">
            <span class="eyebrow">Tutor Portal Balance</span>
            <h1>Earnings &amp; <em>Payouts</em></h1>
            <p>Monitor your completed classroom cycles and disburse available earnings directly to your verified profiles.</p>
          </div>
        </section>
  
        <!-- Financial Cards Summary Grid -->
        <section class="summary">
          <div class="summary-cell featured">
            <h3>Available for Disbursement</h3>
            <div class="price-val">\${{ balances.available }} USD</div>
          </div>
          <div class="summary-cell">
            <h3>Pending Clearance</h3>
            <div class="metric-val-pending">\${{ balances.pending_clearance }}</div>
          </div>
          <div class="summary-cell">
            <h3>Lifetime Gross Earnings</h3>
            <div class="metric-val">\${{ balances.lifetime_earnings }}</div>
          </div>
        </section>
  
        <!-- COMPLETED CLASSES LEDGER TABLE -->
        <main>
          <div class="table-card">
            <div class="table-header-title">
              <h2>Completed Sessions Billing Ledger</h2>
            </div>
            <table class="history-table">
              <thead>
                <tr>
                  <th>Class Ref ID</th>
                  <th>Date Completed</th>
                  <th>Student Profile</th>
                  <th>Subject Matter Details</th>
                  <th>Earnings</th>
                  <th class="text-right">Action State</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="cls in localClasses" :key="cls.id">
                  <td><code class="txn-code">{{ cls.id }}</code></td>
                  <td>{{ cls.date }}</td>
                  <td class="font-medium text-ink">{{ cls.student }}</td>
                  <td class="text-ink-soft text-sm">{{ cls.subject }}</td>
                  <td class="font-bold text-ink">\${{ cls.amount }}</td>
                  <td class="text-right">
                    <button 
                      v-if="cls.can_disburse"
                      @click="openDisbursementModal(cls)"
                      class="row-disburse-btn"
                      type="button"
                    >
                      Disburse Funds
                    </button>
                    <span v-else class="status-badge status-paid">
                      <span class="dot"></span> Disbursed
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </main>
      </div>
  
      <!-- DIALOG MODAL POPUP LAYER BACKDROP CONTAINER -->
      <div v-if="isModalOpen" class="modal-backdrop" @click.self="closeModal">
        <div class="modal-window">
          <div class="modal-header">
            <h2>Disburse Session Earnings</h2>
            <button class="close-x-btn" @click="closeModal" type="button">&times;</button>
          </div>
          <p class="modal-subtitle">Initiate payout collection parameters for transaction reference <strong>{{ selectedClass?.id }}</strong> (\${{ selectedClass?.amount }}).</p>
  
          <form @submit.prevent="submitDisbursementForm" class="modal-form">
            <!-- Selection Method Radio Triggers -->
            <div class="form-group">
              <label class="input-label">Select Withdrawal Channel</label>
              <div class="radio-card-grid">
                <label :class="['radio-card', { active: form.payout_method === 'stripe' }]">
                  <input type="radio" v-model="form.payout_method" value="stripe" />
                  <div class="radio-card-txt">
                    <strong>Stripe Direct Payout</strong>
                    <span>Instant digital bank wire</span>
                  </div>
                </label>
                <label :class="['radio-card', { active: form.payout_method === 'check' }]">
                  <input type="radio" v-model="form.payout_method" value="check" />
                  <div class="radio-card-txt">
                    <strong>Mail Courier Check</strong>
                    <span>Delivered to physical address</span>
                  </div>
                </label>
              </div>
            </div>
  
            <!-- Dynamic Condition Area A: Stripe Details -->
            <div v-if="form.payout_method === 'stripe'" class="animated-fields">
              <div class="form-group">
                <label class="input-label">Account Holder Full Name</label>
                <input type="text" v-model="form.account_name" class="text-input" placeholder="e.g. Melvin Jhons Elma" required />
              </div>
              <div class="form-group">
                <label class="input-label">Stripe Verified Account / Debit Card Number</label>
                <input type="text" v-model="form.account_number" class="text-input" placeholder="e.g. acct_1Gxxxxx or card number" required />
              </div>
            </div>
  
            <!-- Dynamic Condition Area B: Mail Courier Check -->
            <div v-if="form.payout_method === 'check'" class="animated-fields">
              <div class="form-group">
                <label class="input-label">Mailing Address Details</label>
                <textarea v-model="form.mailing_address" class="textarea-input" placeholder="Enter Full Street name, City, Zip Postal Code instructions..." required></textarea>
              </div>
            </div>
  
            <!-- Form CTAs Footers -->
            <div class="modal-footer">
              <button class="btn-cancel" @click="closeModal" type="button">Cancel</button>
              <button class="btn-submit" :disabled="isFormSubmitting" type="submit">
                {{ isFormSubmitting ? 'Processing Payout...' : 'Confirm & Disburse' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, reactive } from 'vue';
  import { router } from '@inertiajs/vue3';
  
  const props = defineProps({
    balances: Object,
    completedClasses: Array
  });
  
  const localClasses = ref([...props.completedClasses]);
  const isModalOpen = ref(false);
  const isFormSubmitting = ref(false);
  const selectedClass = ref(null);
  
  const form = reactive({
    class_id: '',
    payout_method: 'stripe',
    account_name: '',
    account_number: '',
    mailing_address: '',
  });
  
  const openDisbursementModal = (classItem) => {
    selectedClass.value = classItem;
    form.class_id = classItem.id;
    isModalOpen.value = true;
  };
  
  const closeModal = () => {
    isModalOpen.value = false;
    selectedClass.value = null;
  };
  
  const submitDisbursementForm = () => {
    isFormSubmitting.value = true;
    
    // Submit the data directly via an Inertia POST request payload link
    router.post('/tutor/disburse-class', form, {
      onSuccess: () => {
        // On success, mark the class as disbursed locally
        const target = localClasses.value.find(c => c.id === form.class_id);
        if (target) target.can_disburse = false;
        
        isFormSubmitting.value = false;
        closeModal();
      },
      onError: () => {
        isFormSubmitting.value = false;
      }
    });
  };
  </script>
  
  <style scoped>
  /* Scoped Layout Stylesheet Modules */
  .payment-history-body {
    --blue-pale: #C8E4E5;
    --blue-mid: #4A9DA8;
    --blue-deep: #2D5A66;
    --blue-deep-hover: #234852;
    --white: #FFFFFF;
    --bg-soft: #FAFBFC;
    --line: #EAEEF0;
    --ink: #1A2B33;
    --ink-soft: #4A5B63;
    --ink-muted: #8896A0;
    
    --green-bg: #DCEFD8;
    --green-text: #3D7A3F;
    --green-dot: #5BA84F;
    --orange-text: #A35B1A;
  
    background: var(--white);
    color: var(--ink);
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
    transition: filter 0.25s ease;
  }
  
  /* 20% background blur when modal pops up */
  .payment-history-body.modal-open .container {
    filter: blur(4px);
    pointer-events: none;
  }
  
  .container { max-width: 1240px; margin: 0 auto; padding: 36px 40px 80px; }
  .topbar { display: flex; align-items: center; justify-content: space-between; padding-bottom: 24px; border-bottom: 1px solid var(--line); margin-bottom: 44px; }
  .brand { display: flex; align-items: center; gap: 12px; font-family: 'Nunito', sans-serif; font-weight: 800; font-size: 18px; color: var(--blue-deep); }
  .brand-mark { width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, var(--blue-mid) 0%, var(--blue-deep) 100%); display: grid; place-items: center; color: var(--white); }
  .brand small { display: block; font-family: 'Inter', sans-serif; font-style: italic; font-weight: 400; font-size: 11px; color: var(--ink-muted); margin-top: 3px; }
  .nav { display: flex; gap: 2px; font-size: 13px; background: var(--bg-soft); padding: 4px; border-radius: 10px; border: 1px solid var(--line); }
  .nav a { padding: 8px 16px; color: var(--ink-soft); text-decoration: none; border-radius: 7px; font-weight: 500; }
  .nav a.active { background: var(--white); color: var(--blue-deep); font-weight: 600; box-shadow: 0 1px 2px rgba(45, 90, 102, 0.04); }
  .avatar { width: 38px; height: 38px; border-radius: 50%; background: var(--blue-pale); color: var(--blue-deep); display: grid; place-items: center; font-family: 'Nunito', sans-serif; font-weight: 700; border: 1px solid var(--line); }
  
  .page-header { margin-bottom: 36px; }
  .page-title .eyebrow { display: inline-block; font-style: italic; font-size: 13px; color: var(--blue-mid); font-weight: 500; margin-bottom: 10px; }
  .page-title h1 { font-family: 'Nunito', sans-serif; font-weight: 800; font-size: 36px; color: var(--ink); }
  .page-title h1 em { font-style: italic; font-weight: 500; color: var(--blue-mid); }.page-title p { margin-top: 10px; color: var(--ink-soft); max-width: 480px; }.summary { display: grid; grid-template-columns: 1.2fr 1fr 1fr; gap: 16px; margin-bottom: 44px; }.summary-cell { background: var(--white); border: 1px solid var(--line); border-radius: 14px; padding: 22px 24px; }.summary-cell h3 { font-size: 13px; color: var(--ink-soft); margin-bottom: 6px; }.summary-cell.featured { background: linear-gradient(135deg, var(--blue-deep) 0%, var(--blue-mid) 100%); color: var(--white); }.summary-cell.featured h3 { color: var(--blue-pale); }.price-val { font-size: 26px; font-weight: 800; font-family: 'Nunito'; }.metric-val-pending { font-size: 26px; font-weight: 700; color: var(--orange-text); font-family: 'Nunito'; }.metric-val { font-size: 26px; font-weight: 700; color: var(--ink); font-family: 'Nunito'; }.table-card { border: 1px solid var(--line); border-radius: 14px; overflow: hidden; background: var(--white); }.table-header-title { padding: 20px 20px 0px 20px; }.table-header-title h2 { font-family: 'Nunito', sans-serif; font-size: 16px; font-weight: 800; color: var(--ink); }.history-table { width: 100%; border-collapse: collapse; text-align: left; margin-top: 14px; }.history-table th { background: var(--bg-soft); padding: 14px 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink-soft); border-bottom: 1px solid var(--line); border-top: 1px solid var(--line); }.history-table td { padding: 16px 20px; border-bottom: 1px solid var(--line); color: var(--ink-soft); }.history-table tr:last-child td { border-bottom: none; }.txn-code { font-family: monospace; background: var(--bg-soft); padding: 3px 6px; border-radius: 4px; color: var(--blue-deep); }.text-right { text-align: right; }.font-medium { font-weight: 500; }.font-bold { font-weight: 700; }.text-ink { color: var(--ink); }.text-ink-soft { color: var(--ink-soft); }.row-disburse-btn { background: var(--blue-deep); color: var(--white); border: none; padding: 6px 14px; border-radius: 6px; font-family: 'Nunito', sans-serif; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.15s ease; }.row-disburse-btn:hover { background: var(--blue-deep-hover); }.status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }.status-badge .dot { width: 6px; height: 6px; border-radius: 50%; }.status-paid { background: var(--green-bg); color: var(--green-text); }.status-paid .dot { background: var(--green-dot); }/* ─────────── Modal Layout Engine Architecture ─────────── */.modal-backdrop {position: fixed;top: 0; left: 0; width: 100vw; height: 100vh;background: rgba(26, 43, 51, 0.45);display: flex; align-items: center; justify-content: center;z-index: 9999;padding: 20px;}.modal-window {background: var(--white);width: 100%; max-width: 540px;border-radius: 16px;box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);padding: 28px;animation: scaleUp 0.2s cubic-bezier(0.16, 1, 0.3, 1);}@keyframes scaleUp { from { transform: scale(0.96); opacity: 0; } to { transform: scale(1); opacity: 1; } }.modal-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }.modal-header h2 { font-family: 'Nunito'; font-weight: 800; font-size: 20px; color: var(--ink); }.close-x-btn { font-size: 24px; background: none; border: none; color: var(--ink-muted); cursor: pointer; }.modal-subtitle { font-size: 13.5px; color: var(--ink-soft); margin-bottom: 24px; line-height: 1.4; }.modal-form { display: flex; flex-direction: column; gap: 20px; }.form-group { display: flex; flex-direction: column; gap: 6px; }.input-label { font-size: 12.5px; font-weight: 600; color: var(--ink); text-transform: uppercase; letter-spacing: 0.03em; }/* Radio Card Custom Groups */.radio-card-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }.radio-card {border: 1px solid var(--line); border-radius: 10px; padding: 14px;display: flex; gap: 10px; cursor: pointer; transition: all 0.2s ease;}.radio-card:hover { border-color: var(--blue-mid); }.radio-card.active { border-color: var(--blue-deep); background: var(--bg-soft); box-shadow: 0 0 0 3px rgba(45, 90, 102, 0.1); }.radio-card input { margin-top: 3px; accent-color: var(--blue-deep); }.radio-card-txt strong { display: block; font-size: 13.5px; color: var(--ink); }.radio-card-txt span { font-size: 11px; color: var(--ink-muted); }/* Form Field Inputs Styles */.text-input, .textarea-input {width: 100%; border: 1px solid var(--line); border-radius: 8px;padding: 10px 14px; font-family: 'Inter'; font-size: 13.5px; color: var(--ink);transition: border-color 0.2s ease;}.text-input:focus, .textarea-input:focus { border-color: var(--blue-deep); outline: none; }.textarea-input { height: 74px; resize: none; }.modal-footer { display: flex; justify-content: flex-end; gap: 12px; margin-top: 12px; }.btn-cancel { background: var(--bg-soft); color: var(--ink-soft); border: 1px solid var(--line); padding: 10px 18px; border-radius: 8px; font-weight: 600; font-size: 13.5px; cursor: pointer; }.btn-submit { background: var(--blue-deep); color: var(--white); border: none; padding: 10px 22px; border-radius: 8px; font-family: 'Nunito'; font-weight: 700; font-size: 14px; cursor: pointer; transition: background 0.2s ease; }.btn-submit:hover { background: var(--blue-deep-hover); }.btn-submit:disabled { opacity: 0.6; cursor: not-allowed; } </style>