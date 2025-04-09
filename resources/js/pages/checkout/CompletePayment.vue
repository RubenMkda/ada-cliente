<script lang="ts">
import { defineComponent } from 'vue';
import CheckoutLayout from '@/layouts/CheckoutLayout.vue';

interface Order {
  id: string | number;
  total_amount: string | number;
  status: string;
}

interface Vehicle {
  id: string | number;
  make: {
    name: string;
  };
  model: {
    name: string;
  };
  year: string | number;
  VIN: string;
}

interface FormData {
  accountNumber: string;
  amount: string;
  reference: string;
}

export default defineComponent({
  components: {
    CheckoutLayout,
  },
  props: {
    order: {
      type: Object as () => Order,
      required: true,
    },
    vehicle: {
      type: Object as () => Vehicle,
      required: true,
    },
  },
  data() {
    return {
      isModalOpen: false,
      currentPaymentMethod: '',
      formData: {
        accountNumber: '',
        amount: '',
        reference: '',
      } as FormData,
    };
  },
  methods: {
    payWithStripe(): void {
      this.$inertia.get(route('vehicles.checkout.process', { vehicle: this.vehicle.id }));
    },
    openModal(method: string): void {
      this.currentPaymentMethod = method;
      this.isModalOpen = true;
    },
    closeModal(): void {
      this.isModalOpen = false;
      this.formData = {
        accountNumber: '',
        amount: '',
        reference: '',
      };
    },
    submitPayment(): void {
      this.$inertia.post(
        route('vehicles.checkout.processAlternativePayment', { order: this.order.id }), 
        {
          payment_method: this.currentPaymentMethod,
          ...this.formData,
          order_id: this.order.id,
          vehicle_id: this.vehicle.id,
        },
        {
          onSuccess: () => {
           
          },
          onError: (errors) => {
            this.$inertia.visit(route('checkout-cancel'), {
              method: 'get',
              data: { message: 'Error al procesar el pago' },
            });
          },
        }
      );
    },
  },
});
</script>

<template>
    <CheckoutLayout>
      <div class="flex flex-col space-y-4 text-gray-700 bg-gray-100 p-8 rounded">
        <dl>
        <dt id="logo">
          <img src="/images/logo-principal.png" class="h-20 w-30" alt="Logo">
        </dt>
        <dd>
          <h1 class="text-7xl tracking-wider font-helvetica text-center">{{ vehicle.make.name }} {{ vehicle.model.name }}</h1>
        </dd>
        </dl>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div class="flex flex-col p-4 bg-gray-50 rounded-lg">
                
            <form class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <div>
              <div class="mt-6 w-full space-y-6 sm:mt-8 lg:mt-0 lg:max-w-xs xl:max-w-md">
                <h2 class="text-4xl tracking-wider font-helvetica text-center py-4">Detalles de la Orden</h2>
                <div class="flow-root">
                  <div class="-my-3 divide-y divide-gray-200 dark:divide-gray-800">
                    <dl class="flex items-center justify-between gap-4 py-3">
                      <dt class="text-base font-normal text-gray-900">Orden ID: </dt>
                      <dd class="text-base font-medium text-gray-900">{{ order.id }}</dd>
                    </dl>                    
                    <dl class="flex items-center justify-between gap-4 py-3">
                      <dt class="text-base font-normal text-gray-900">Estado:</dt>
                      <dd class="text-base font-medium text-gray-900">{{ order.status }}</dd>
                    </dl>
                    <dl class="flex items-center justify-between gap-4 py-3">
                      <dt class="text-base font-normal text-gray-900">Año:</dt>
                      <dd class="text-base font-medium text-gray-900">{{ vehicle.year }}</dd>
                    </dl>
                    <dl class="flex items-center justify-between gap-4 py-3">
                      <dt class="text-base font-normal text-gray-900">VIN</dt>
                      <dd class="text-base font-medium text-gray-900">{{ vehicle.VIN }}</dd>
                    </dl>
                    <dl class="flex items-center justify-between gap-10 py-3">
                      <dt class="text-base font-bold text-gray-900">Precio:</dt>
                      <dd class="text-base font-bold text-gray-900">{{ order.total_amount }}$</dd>
                    </dl>
                  </div>
                </div>
              </div>
            </div>
          </form>
            </div>
    
            
            <div class="flex flex-col space-y-4 p-4 bg-gray-50 rounded-lg">
                <h2 class="text-4xl tracking-wider font-helvetica text-center py-4">Completar Pago</h2>
            <button
                @click="payWithStripe"
                class="bg-primary text-white px-4 py-2 rounded font-bold"
            >
                Pagar con Stripe
            </button>
    
            <button
                @click="openModal('zelle')"
                class="bg-primary text-white px-4 py-2 rounded font-bold"
            >
                Pagar con Zelle
            </button>
    
            <button
                @click="openModal('transfer')"
                class="bg-primary text-white px-4 py-2 rounded font-bold"
            >
                Pagar con Transferencia
            </button>
    
            <button
                @click="openModal('deposit')"
                class="bg-primary text-white px-4 py-2 rounded font-bold"
            >
                Pagar con Depósito Bancario
            </button>
            </div>
        </div>
        
        <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
          <div class="bg-white p-8 rounded-lg">
            <h3 class="text-2xl font-bold mb-4">Pagar con {{ currentPaymentMethod }}</h3>
            <form @submit.prevent="submitPayment">
              <div class="space-y-4">
                <input type="text" v-model="formData.accountNumber" placeholder="Número de cuenta" class="w-full p-2 border rounded">
                <input type="text" v-model="formData.amount" placeholder="Monto" class="w-full p-2 border rounded">
                <input type="text" v-model="formData.reference" placeholder="Referencia" class="w-full p-2 border rounded">
              </div>
              <div class="mt-4 flex justify-end space-x-4">
                <button type="button" @click="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded">Cancelar</button>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded">Pagar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </CheckoutLayout>
</template>