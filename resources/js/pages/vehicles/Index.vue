<script setup lang="ts">
import { reactive, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import HomeLayout from '@/layouts/HomeLayout.vue';

interface Vehicle {
  id: number;
  make: {
    name: string;
  };
  model: {
    name: string;
  };
  year: number;
  price: number;
  status: string;
  photos?: Array<{
    photo_path: string;
  }>;
}

interface PaginatedData<T> {
  data: T[];
  prev_page_url: string | null;
  next_page_url: string | null;
}

interface Make {
  id: number;
  name: string;
}

interface Model {
  id: number;
  name: string;
  make_id: number;
}

interface PriceRange {
  min: number;
  max: number;
}

interface Filters {
  make_id?: string | number;
  model_id?: string | number;
  year?: string | number;
  min_price?: number;
  max_price?: number;
  status?: string;
}

const props = defineProps<{
  vehicles: PaginatedData<Vehicle>;
  filters: Filters;
  makes: Make[];
  models: Model[];
  priceRange: PriceRange;
  canLogin: boolean;
  canRegister: boolean;
}>();

const form = reactive<Filters>({
  make_id: props.filters.make_id || '',
  model_id: props.filters.model_id || '',
  year: props.filters.year || '',
  min_price: props.filters.min_price ?? props.priceRange.min,
  max_price: props.filters.max_price ?? props.priceRange.max,
  status: props.filters.status || '',
});

const filteredModels = computed(() => {
  if (!form.make_id) return [];
  return props.models.filter(model => model.make_id === Number(form.make_id));
});

const updateMinPrice = (e: Event) => {
  const target = e.target as HTMLInputElement;
  form.min_price = Math.min(Number(target.value), form.max_price as number);
};

const updateMaxPrice = (e: Event) => {
  const target = e.target as HTMLInputElement;
  form.max_price = Math.max(Number(target.value), form.min_price as number);
};

const applyFilters = () => {
  router.get(route('vehicles.index'), {
    ...form,
    min_price: form.min_price !== props.priceRange.min ? form.min_price : null,
    max_price: form.max_price !== props.priceRange.max ? form.max_price : null,
  }, {
    preserveState: true,
    replace: true,
  });
};

watch(
  () => ({ ...form }),
  () => {
    applyFilters(); 
  },
  { deep: true }
);
</script>

<template>
  <HomeLayout :can-login="canLogin" :can-register="canRegister">
    <div class="container mx-auto px-4">
      <h1 class="text-3xl font-bold py-6">Lista de Vehículos</h1>

      <div class="rounded-lg shadow-md p-6 mb-6 grid grid-cols-3">
        <div class=" col-span-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          <a 
            v-for="vehicle in vehicles.data" 
            :key="vehicle.id" 
            :href="route('vehicles.show', vehicle.id)" 
            class="bg-white rounded-lg shadow-md overflow-hidden block hover:shadow-lg transition-shadow"
          >
            <img 
              v-if="vehicle.photos && vehicle.photos.length > 0"
              :src="`/storage/${vehicle.photos[0].photo_path}`" 
              alt="Foto del vehículo" 
              class="w-full h-48 object-cover"
            />
            <img 
              v-else
              src="/images/placeholder-car.jpg" 
              alt="Foto no disponible" 
              class="w-full h-48 object-cover"
            />
  
            <div class="p-4">
              <h2 class="text-lg font-semibold">{{ vehicle.make.name }} {{ vehicle.model.name }}</h2>
              <p class="text-gray-600">{{ vehicle.year }}</p>
              <p class="text-gray-800 font-bold">{{ vehicle.price }} €</p>
              <p class="text-sm text-gray-500">Estado: {{ vehicle.status }}</p>
            </div>
          </a>
        </div>
        
        <div class="bg-primary rounded-lg shadow-md p-6">
            <form @submit.prevent="applyFilters" class="space-y-4">
            <div class="grid grid-cols-1">
                <!-- Select Marca -->
                <div>
                <label class="block text-sm font-bold text-gray-200">Marca</label>
                <select v-model="form.make_id" class="px-4 py-2">
                    <option value="">Selecciona una marca</option>
                    <option v-for="make in makes" :key="make.id" :value="make.id">
                    {{ make.name }}
                    </option>
                </select>
                </div>

                <!-- Select Modelo -->
                <div>
                <label class="block text-sm font-bold text-gray-200">Modelo</label>
                <select v-model="form.model_id" class="px-4 py-2">
                    <option value="">Selecciona un modelo</option>
                    <option v-for="model in filteredModels" :key="model.id" :value="model.id">
                    {{ model.name }}
                    </option>
                </select>
                </div>

                <div>
                <label class="block text-sm font-bold text-gray-200">Año</label>
                <input
                    type="number"
                    v-model="form.year"
                    class="mt-1 block px-4 py-2 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Año"
                />
                </div>
            </div>

            <!-- Rango de Precio -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-200">Rango de Precio (€)</label>
                <div class="flex items-center gap-4">
                <div class="flex-1">
                    <input
                    type="range"
                    v-model="form.min_price"
                    :min="priceRange.min"
                    :max="priceRange.max"
                    step="100"
                    class="w-full"
                    @input="updateMinPrice"
                    />
                    <span class="text-sm text-gray-200">{{ form.min_price }} €</span>
                </div>
                <div class="flex-1">
                    <input
                    type="range"
                    v-model="form.max_price"
                    :min="priceRange.min"
                    :max="priceRange.max"
                    step="100"
                    class="w-full"
                    @input="updateMaxPrice"
                    />
                    <span class="text-sm text-gray-200">{{ form.max_price }} €</span>
                </div>
                </div>
            </div>
            </form>
        </div>
      </div>


      <div class="mt-6 flex justify-center space-x-2">
        <button
          v-if="vehicles.prev_page_url"
          @click="$inertia.visit(vehicles.prev_page_url)"
          class="px-4 py-2 bg-gray-200 border rounded hover:bg-gray-300"
        >
          Anterior
        </button>
        <button
          v-if="vehicles.next_page_url"
          @click="$inertia.visit(vehicles.next_page_url)"
          class="px-4 py-2 bg-gray-200 border rounded hover:bg-gray-300"
        >
          Siguiente
        </button>
      </div>
    </div>
  </HomeLayout>
</template>

<style scoped>
</style>