<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-dt';
import { ref, onMounted } from 'vue';

DataTable.use(DataTablesCore);

const columns = [
  { data: 'id', title: 'ID' },
  { data: 'amount', title: 'Amount', render: (data) => `$${parseFloat(data).toFixed(2)}` },
  { data: 'type', title: 'Type', render: (data) => data.replace('_', ' ').toUpperCase() },
  { data: 'status', title: 'Status', render: (data) => data.toUpperCase() },
  { data: 'payment_method', title: 'Payment Method' },
];

const options = {
  responsive: true,
  select: true,
  columnDefs: [
    { targets: [0], width: '5%' },
    { targets: [1, 2, 3, 4], width: '10%' },
  ]
};

const props = defineProps<{
  transactions: Array<{
    id: number;
    order_id: number;
    user_id: number;
    amount: number;
    type: string;
    status: string;
    payment_method: string;
    transaction_date: string;
  }>;
}>();

onMounted(() => {
  const table = document.querySelector('.display');

  table?.addEventListener('click', (event) => {
    const target = event.target as HTMLElement;

    if (target.classList.contains('btn-edit')) {
      const transactionId = target.getAttribute('data-id');
      const transaction = props.transactions.find(t => t.id === parseInt(transactionId));
      if (transaction) {
        openEditModal(transaction);
      }
    }

    if (target.classList.contains('btn-delete')) {
      const transactionId = target.getAttribute('data-id');
      if (confirm('Are you sure you want to delete this transaction?')) {
        deleteTransaction(parseInt(transactionId));
      }
    }
  });
});

const deleteTransaction = (transactionId: number) => {
  console.log('Delete transaction:', transactionId);
  // Add your delete logic here
};

const openEditModal = (transaction: any) => {
  console.log('Edit transaction:', transaction);
  // Add your edit modal logic here
};
</script>

<template>
  <Head title="Transactions" />

  <AppLayout>
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <h1 class="text-xl font-semibold">Transactions</h1>
      <DataTable :columns="columns" :data="transactions" :options="options" class="display" />
    </div>
  </AppLayout>
</template>

<style>
  @import 'datatables.net-dt';
</style>