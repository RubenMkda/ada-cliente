<script setup>
  import { Swiper, SwiperSlide } from 'swiper/vue';
  import 'swiper/css';
  import 'swiper/css/pagination';
  import { Pagination } from 'swiper/modules';
  import { defineProps } from 'vue';

  const props = defineProps({
    vehicles: {
      type: Object,
      required: true
    }
  });

  const modules = [Pagination];
</script>

<template>
  <swiper
    :slidesPerView="3"
    :spaceBetween="30"
    :pagination="{
      clickable: true,
    }"
    :modules="modules"
    class="mySwiper"
  >
    <swiper-slide v-for="vehicle in vehicles.data" :key="vehicle.id">
      <div class="slide-content">
        <img 
          v-if="vehicle.photos.length > 0" 
          :src="vehicle.photos[0].path" 
          :alt="vehicle.model.name"
          class="vehicle-image"
        >
        <div class="vehicle-info">
          <h3>{{ vehicle.make.name }} {{ vehicle.model.name }}</h3>
          <p>Año: {{ vehicle.year }}</p>
          <p>Precio: ${{ vehicle.price.toLocaleString() }}</p>
        </div>
      </div>
    </swiper-slide>
  </swiper>
</template>

<style scoped>
.vehicle-image {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-radius: 8px;
}

.slide-content {
  padding: 10px;
  background: #f5f5f5;
  border-radius: 8px;
  height: 100%;
}

.vehicle-info {
  padding: 10px 0;
}

.mySwiper {
  padding: 20px 0;
}
</style>
