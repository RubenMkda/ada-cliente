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
  { data: 'platform', title: 'Platform' },
  { data: 'vin', title: 'VIN' },
  { data: 'max_bid', title: 'Max Bid' },
  { data: 'status', title: 'Status' },
];

const options = {
  responsive: true,
  select: true,
};

const props = defineProps<{
  auctionRequests: Array<{
    id: number;
    user_id: number;
    platform: 'Copart' | 'IAAI' | 'Manheim' | 'ACV Auctions';
    vin: string;
    lot_number: string | null;
    vehicle_location: string;
    max_bid: number;
    auction_url: string | null;
    transport_quote: boolean;
    carfax_quote: boolean;
    comments: string | null;
    service_fee: number;
    stripe_price_id: string | null;
    stripe_product_id: string | null;
    status: 'pending' | 'active' | 'completed' | 'cancelled';
  }>;
}>();

onMounted(() => {
  const table = document.querySelector('.display');

  table?.addEventListener('click', (event) => {
    const target = event.target as HTMLElement;

    if (target.classList.contains('btn-edit')) {
      const requestId = target.getAttribute('data-id');
      const request = props.auctionRequests.find(request => request.id === parseInt(requestId));
    }
  });
});
</script>

<template>
  <Head title="Auction Requests" />

  <AppLayout>
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <h1 class="text-xl font-semibold">Auction Requests</h1>
      <DataTable :columns="columns" :data="auctionRequests" :options="options" class="display" />
    </div>
  </AppLayout>
</template>

<style>
  @import 'datatables.net-dt';
</style>
