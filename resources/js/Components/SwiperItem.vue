<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/navigation";

import { Navigation } from "swiper/modules";

import ShowImage from "@/Components/ShowImage.vue";

const modules = [Navigation];
const props = defineProps(["product"]);
</script>

<template>
  <swiper
    :navigation="true"
    :modules="modules"
    class="mySwiper z-0 position-relative"
  >
    <swiper-slide>
      <ShowImage
        v-if="product.image_first && product.image_first.filename"
        insertAlt="team"
        insertClass="flex-shrink-0 rounded-lg w-80 h-48 object-cover object-center sm:mb-0 mb-4"
        :fileName="product.image_first.filename"
        folderName="products"
      ></ShowImage>
    </swiper-slide>
    <swiper-slide>
      <ShowImage
        v-if="product.image_second && product.image_second.filename"
        insertAlt="team"
        insertClass="flex-shrink-0 rounded-lg w-80 h-48 object-cover object-center sm:mb-0 mb-4"
        :fileName="product.image_second.filename"
        folderName="products"
      ></ShowImage>
    </swiper-slide>
    <swiper-slide>
      <ShowImage
        v-if="product.image_third && product.image_third.filename"
        insertAlt="team"
        insertClass="flex-shrink-0 rounded-lg w-80 h-48 object-cover object-center sm:mb-0 mb-4"
        :fileName="product.image_third.filename"
        folderName="products"
      ></ShowImage>
    </swiper-slide>
    <swiper-slide>
      <ShowImage
        v-if="product.image_fourth && product.image_fourth.filename"
        insertAlt="team"
        insertClass="flex-shrink-0 rounded-lg w-80 h-48 object-cover object-center sm:mb-0 mb-4"
        :fileName="product.image_fourth.filename"
        folderName="products"
      ></ShowImage>
    </swiper-slide>
  </swiper>
</template>
<style scoped>
.swiper {
  width: 100%;
  height: 100%;
}

.swiper-slide {
  text-align: center;
  font-size: 18px;
  background: #fff;

  /* Center slide text vertically */
  display: flex;
  justify-content: center;
  align-items: center;
}

.swiper-slide img {
  display: block;
  width: 100%;
  height: 100%;
  object-fit: cover;
}
</style>
