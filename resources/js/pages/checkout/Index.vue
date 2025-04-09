<script>
import CheckoutLayout from '@/layouts/CheckoutLayout.vue';

export default {
  components: {
    CheckoutLayout,
  },
  props: {
    vehicle: Object,
    vehicleMake: String,
    vehicleModel: String,
  },
  methods: {
    proceedToPayment() {
      this.$inertia.post(route('vehicles.checkout.createOrder', { vehicle: this.vehicle.id }));
    },
  },
};
</script>

<template>
  <CheckoutLayout>
    <div class="flex flex-col space-y-4 text-gray-700 bg-gray-100 p-8 rounded">
      <dl>
        <dt id="logo">
          <img src="/images/logo-principal.png" class="h-20 w-30" alt="Logo">
        </dt>
        <dd>
          <h1 class="text-7xl tracking-wider font-helvetica text-center">{{ vehicleMake }} {{ vehicleModel }}</h1>
        </dd>
      </dl>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">


        <div class="flex flex-col bg-gray-50 rounded-lg">
          <form class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <div>
              <div class="mt-6 w-full space-y-6 sm:mt-8 lg:mt-0 lg:max-w-xs xl:max-w-md">
                <h2 class="text-4xl tracking-wider font-helvetica text-center py-4">Especificaciones</h2>
                <div class="flow-root">
                  <div class="-my-3 divide-y divide-gray-200 dark:divide-gray-800">
                    <dl class="flex items-center justify-between gap-4 py-3">
                      <dt class="text-base font-normal text-gray-900">Precio:</dt>
                      <dd class="text-base font-medium text-gray-900">{{ vehicle.price }}$</dd>
                    </dl>
                    <dl class="flex items-center justify-between gap-4 py-3">
                      <dt class="text-base font-normal text-gray-900">Año:</dt>
                      <dd class="text-base font-medium text-gray-900">{{ vehicle.year }}</dd>
                    </dl>
                    <dl class="flex items-center justify-between gap-4 py-3">
                      <dt class="text-base font-normal text-gray-900">VIN:</dt>
                      <dd class="text-base font-medium text-gray-900">{{ vehicle.VIN }}</dd>
                    </dl>
                    <dl class="flex items-center justify-between gap-4 py-3">
                      <dt class="text-base font-normal text-gray-900">Estado:</dt>
                      <dd class="text-base font-medium text-gray-900">{{ vehicle.status }}</dd>
                    </dl>
                    <dl class="flex items-center justify-between gap-10 py-3">
                      <dt class="text-base font-bold text-gray-900">Total</dt>
                      <dd class="text-base font-bold text-gray-900">{{ vehicle.price }}$</dd>
                    </dl>
                  </div>
                </div>
              </div>
            </div>
          </form>
        <div class="mx-auto max-w-screen-xl px-4 py-6 2xl:px-0">
          <button @click="proceedToPayment" class="bg-primary text-white px-4 py-2 rounded font-bold">Pagar Ahora</button>
        </div>
        </div>


        <div class="flex flex-col bg-gray-50 rounded-lg">
          <img
          v-for="photo in vehicle.photos"
          :key="photo.id"
          :src="`/storage/${photo.photo_path}`" 
          :alt="`Imagen del vehículo ${vehicle.make.name} ${vehicle.model.name}`"
          class="w-64 h-64 object-cover rounded-md"
          />
        </div>
      </div>
    </div>
  </CheckoutLayout>
</template>
