<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-dt';
import { ref, onMounted } from 'vue';

DataTable.use(DataTablesCore);

const columns = [
  { data: 'id', title: 'ID' },
  { data: 'user_id', title: 'User ID' },
  { data: 'type', title: 'Type' },
  { data: 'total_amount', title: 'Total Amount' },
  { data: 'status', title: 'Status' },
  {
    data: null,
    title: 'Actions',
    render: function (data, type, row) {
      return `
        <button class="btn-edit" data-id="${row.id}">Edit</button>
        <button class="btn-delete" data-id="${row.id}">Delete</button>
      `;
    },
  },
];

const options = {
  responsive: true,
  select: true,
};

const props = defineProps<{
  orders: Array<{
    id: number;
    user_id: number;
    type: string;
    total_amount: number;
    status: string;
    broker_fees: Array<{
      id: number;
      order_id: number;
      broker_fee: number;
    }>;
  }>;
}>();

onMounted(() => {
  const table = document.querySelector('.display');

  table?.addEventListener('click', (event) => {
    const target = event.target as HTMLElement;

    if (target.classList.contains('btn-edit')) {
      const orderId = target.getAttribute('data-id');
      const order = props.orders.find(order => order.id === parseInt(orderId));
      if (order) {
        openEditModal(order);
      }
    }


  });
});

const deleteOrder = (orderId: number) => {
  console.log('Delete order:', orderId);
};
</script>

<template>
  <Head title="Orders" />

  <AppLayout>
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <h1 class="text-xl font-semibold">Transactions</h1>
      <DataTable :columns="columns" :data="orders" :options="options" class="display" />
    </div>
  </AppLayout>
</template>

<style>
  @import 'datatables.net-dt';
</style>