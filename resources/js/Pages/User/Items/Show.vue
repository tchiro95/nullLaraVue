<script setup>
import AuthenticatedLayout from "@/Layouts/UserAuthenticatedLayout.vue";
import ShowImage from "@/Components/ShowImage.vue";

import Swiper from "@/Components/SwiperItem.vue";
import modalshop from "@/Components/Modals/ModalShop.vue";
import { Head, useForm } from "@inertiajs/vue3";

// import Swiper JS

const props = defineProps({
  product: Object,
  quantity: Number,
});
const quantitiesNumber = props.quantity > 9 ? 9 : props.quantity;

const form = useForm({
  quantity: 0,
  product_id: props.product.id,
});
const submit = () => {
  form.post(route("user.cart.add"));
};
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        教材の詳細
      </h2>
      <div v-if="$page.props.auth.user">ログイン</div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <section class="text-gray-600 body-font overflow-hidden">
              <div class="container px-5 py-4 mx-auto">
                <div class="lg:w-4/5 mx-auto flex flex-wrap">
                  <div class="md:w-1/2 w-full md:pl-10 mt-6 md:mt-0">
                    <Swiper :product="product"></Swiper>
                  </div>
                  <form @submit.prevent="submit">
                    <div class="md:w-1/2 w-full md:pl-10 mt-6 md:mt-0">
                      <h2
                        class="text-sm title-font text-gray-500 tracking-widest"
                      >
                        {{ product.category.primary.name }}:
                        {{ product.category.name }}
                      </h2>
                      <h1
                        class="text-gray-900 text-3xl title-font font-medium mb-1"
                      >
                        {{ product.name }}
                      </h1>
                      <p class="leading-relaxed">
                        {{ product.information }}
                      </p>
                      <div class="flex flex-row h-12 mb-4">
                        <div
                          class="title-font font-medium text-2xl text-gray-900 mr-10 my-auto"
                        >
                          {{ product.price }}円
                        </div>
                        <div>
                          <span>数量：</span>
                          <select
                            class="rounded border appearance-none border-gray-300 py-2 focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-500 text-base pl-3 pr-10 my-auto"
                            v-model="form.quantity"
                          >
                            <option
                              v-for="n in quantitiesNumber"
                              :key="n"
                              :value="n"
                            >
                              {{ n }}
                            </option>
                          </select>
                        </div>
                      </div>
                      <div class="flex justify-end">
                        <button
                          class="flex ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded"
                        >
                          登録
                        </button>
                        <button
                          class="flex ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded"
                        >
                          購入
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="border-t border-gray-300 my-4"></div>
                <!-- z-indexはposition:static(無指定)意外とのセットで効く。親の影響を受けるから、できるだけ大きい親に効かせる。 -->
                <div
                  class="flex md:flex-row flex-col justify-center z-50 relative"
                >
                  <div class="mr-4 md:mb-2 mb-4">
                    <ShowImage
                      v-if="product.shop.filename"
                      insertAlt="shop"
                      insertClass="flex-shrink-0 rounded-lg h-24 object-cover object-center sm:mb-0 mb-4"
                      :fileName="product.shop.filename"
                      folderName="shops"
                    ></ShowImage>
                    <ShowImage
                      v-else
                      insertAlt="noimage"
                      insertClass="flex-shrink-0 rounded-lg w-24 h-24 object-cover object-center sm:mb-0 mb-4"
                      fileName=""
                      folderName="products"
                    ></ShowImage>
                  </div>
                  <div class="md:w-1/2 ml-4 text-left">
                    <div>
                      <span class="text-sm text-gray-700">教材提供:</span>
                      {{ product.shop.name }}
                    </div>
                    <!-- モーダル-->
                    <modalshop :shop="product.shop"></modalshop>
                    <!-- モーダル-->
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
