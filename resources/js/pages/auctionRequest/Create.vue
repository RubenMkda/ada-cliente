<script setup lang="ts">
import { reactive, ref, watch } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import HomeLayout from '@/layouts/HomeLayout.vue';

const props = defineProps<{
    platforms: string[];
    serviceFeeRules: {
        min_amount: number;
        max_amount: number | null;
        fee: number;
    }[];
}>();

const form = useForm({
    platform: '',
    vin: '',
    lot_number: '',
    vehicle_location: '',
    max_bid: '',
    auction_url: '',
    transport_quote: false,
    carfax_quote: false,
    comments: '',
    service_fee: 0,
});

const calculateServiceFee = (amount: number) => {
    const rule = props.serviceFeeRules.find(rule =>
        amount >= rule.min_amount &&
        (rule.max_amount === null || amount <= rule.max_amount)
    );
    return rule ? rule.fee : 0;
};

watch(() => form.max_bid, (newBid) => {
    const bid = parseFloat(newBid);
    if (!isNaN(bid)) {
        form.service_fee = calculateServiceFee(bid);
    } else {
        form.service_fee = 0;
    }
});

const submit = () => {
    form.post(route('auction-requests.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>


<template>
    <HomeLayout>
        <div class="max-w-3xl mx-auto px-4 py-10">
            <h1 class="text-3xl font-bold mb-8 text-gray-800">Crear Solicitud de Subasta</h1>

            <form @submit.prevent="submit" class="space-y-6 bg-greenBold shadow rounded-xl p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">Plataforma</label>
                        <select v-model="form.platform" class="w-full bg-white rounded-md shadow-sm px-1 py-2">
                            <option disabled value="">Seleccione una plataforma</option>
                            <option v-for="platform in props.platforms" :key="platform" :value="platform">
                                {{ platform }}
                            </option>
                        </select>
                        <p v-if="form.errors.platform" class="text-sm text-red-500">{{ form.errors.platform }}</p>
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">VIN</label>
                        <input
                            type="text"
                            v-model="form.vin"
                            class="w-full border-gray-300 rounded-md shadow-sm uppercase px-1 py-2"
                            maxlength="17"
                        />
                        <p v-if="form.errors.vin" class="text-sm text-red-500">{{ form.errors.vin }}</p>
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">Número de lote</label>
                        <input type="text" v-model="form.lot_number" class="w-full px-1 py-2 border-gray-300 rounded-md shadow-sm" />
                        <p v-if="form.errors.lot_number" class="text-sm text-red-500">{{ form.errors.lot_number }}</p>
                    </div>

                    <!-- Ubicación -->
                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">Ubicación del vehículo</label>
                        <input
                            type="text"
                            v-model="form.vehicle_location"
                            class="w-full border-gray-300 rounded-md shadow-sm px-1 py-2"
                        />
                        <p v-if="form.errors.vehicle_location" class="text-sm text-red-500">{{ form.errors.vehicle_location }}</p>
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">Máxima puja ($)</label>
                        <input
                            type="number"
                            v-model="form.max_bid"
                            step="0.01"
                            class="w-full border-gray-300 rounded-md shadow-sm px-1 py-2"
                        />
                        <p v-if="form.errors.max_bid" class="text-sm text-red-500">{{ form.errors.max_bid }}</p>
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">URL de la subasta</label>
                        <input type="url" v-model="form.auction_url" class="w-full border-gray-300 rounded-md shadow-sm px-1 py-2" />
                        <p v-if="form.errors.auction_url" class="text-sm text-red-500">{{ form.errors.auction_url }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <div class="flex flex-wrap gap-4 items-center justify-center">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" v-model="form.transport_quote" />
                                <span class="text-gray-300">Solicitar cotización de transporte</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" v-model="form.carfax_quote" />
                                <span class="text-gray-300">Solicitar informe Carfax</span>
                            </label>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block font-semibold text-gray-300 mb-1">Comentarios</label>
                        <textarea
                            v-model="form.comments"
                            class="w-full border-gray-300 rounded-md shadow-sm px-1 py-2"
                            rows="3"
                        ></textarea>
                        <p v-if="form.errors.comments" class="text-sm text-red-500">{{ form.errors.comments }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block font-medium">Tarifa de servicio ($)</label>
                        <input type="number" v-model="form.service_fee" step="0.01" class="w-full border p-2 rounded bg-gray-100" readonly />
                        <div v-if="form.errors.service_fee" class="text-red-500 text-sm">{{ form.errors.service_fee }}</div>
                    </div>
                </div>

                <!-- Botón -->
                <div class="pt-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="bg-primary text-white font-semibold px-6 py-2 rounded hover:bg-primary/80 disabled:opacity-50"
                    >
                        Enviar Solicitud
                    </button>
                </div>
            </form>

        </div>
    </HomeLayout>
</template>
