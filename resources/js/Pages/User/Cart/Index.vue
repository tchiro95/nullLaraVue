<script setup>
import AuthenticatedLayout from "@/Layouts/UserAuthenticatedLayout.vue";
import ShowImage from "@/Components/ShowImage.vue";

import { Head, useForm, Link } from "@inertiajs/vue3";
import { defineProps } from "vue";

// import Swiper JS

const props = defineProps({
  products: Array,
  totalPrice: Number,
});

const form = useForm({
  totalPrice: Math.round(props.totalPrice * 1.1),
  products: props.products,
});

const deleteForm = useForm({
  productname: "",
  cartid: 0,
});

const deleteItem = (cartProduct) => {
  deleteForm.cartid = cartProduct.pivot.id;
  deleteForm.productname = cartProduct.name;
  deleteForm.post(route("user.cart.delete"));
};

const submit = () => {
  form.post(route("user.cart.checkout"));
};
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        カートの中身
      </h2>
    </template>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div
            v-if="$page.props.flash.status === 'message'"
            class="p-2 bg-blue-300"
          >
            {{ $page.props.flash.message }}
          </div>
          <div
            v-if="$page.props.flash.status === 'alert'"
            class="p-2 bg-red-300"
          >
            {{ $page.props.flash.message }}
          </div>
          <div class="p-6 text-gray-900">
            <div class="flex justify-end">
              <Link :href="route('user.items.index')">
                <div
                  class="text-white bg-gray-500 border-0 py-1 px-6 focus:outline-none hover:bg-gray-600 rounded my-4 w-42 mx-auto text-center"
                >
                  教材選択に戻る
                </div>
              </Link>
            </div>
            <div
              v-if="products.length < 1"
              class="w-full flex-col justify-center"
            >
              <p class="text-center">カートに商品が入っていません。</p>
            </div>
            <section
              v-else
              class="text-gray-600 body-font overflow-hidden my-auto w-full md:w-2/3 text-center"
            >
              <table
                class="table-auto inline-block text-left whitespace-no-wrap text-sm md:text-base"
              >
                <thead>
                  <tr>
                    <th
                      class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl"
                    >
                      画像
                    </th>
                    <th
                      class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl"
                    >
                      商品名
                    </th>
                    <th
                      class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"
                    >
                      価格
                    </th>
                    <th
                      class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"
                    >
                      個数
                    </th>
                    <th
                      class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"
                    >
                      金額
                    </th>
                    <th
                      class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"
                    >
                      削除
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="product in products"
                    :key="product.id"
                    class="py-2"
                  >
                    <td class="px-4">
                      <ShowImage
                        v-if="
                          product.image_first && product.image_first.filename
                        "
                        insertAlt="team"
                        insertClass="flex-shrink-0 rounded-lg w-20 h-auto object-cover object-center sm:mb-0 mb-4"
                        :fileName="product.image_first.filename"
                        folderName="products"
                      ></ShowImage>
                      <ShowImage
                        v-else
                        insertAlt="noimage"
                        insertClass="flex-shrink-0 rounded-lg w-20 h-auto object-cover object-center sm:mb-0 mb-4"
                        fileName=""
                        folderName="products"
                      ></ShowImage>
                    </td>
                    <td class="px-4">{{ product.name }}</td>
                    <td class="px-4">{{ product.price }}円</td>
                    <td class="px-4">{{ product.pivot.quantity }}個</td>
                    <td class="px-4 text-right">
                      {{
                        new Intl.NumberFormat("ja-JP").format(
                          product.pivot.quantity * product.price
                        )
                      }}円
                    </td>
                    <td>
                      <button
                        class="flex ml-auto py-1 px-2 cursor-pointer"
                        @click="deleteItem(product)"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                          class="w-5 h-5"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                          />
                        </svg>
                      </button>
                    </td>
                  </tr>
                  <tr class="border-t-2 border-gray-400">
                    <td colspan="2" class="px-4 pt-4"></td>
                    <td colspan="2" class="px-4 pt-2">小計：</td>
                    <td class="px-4 text-right">
                      {{ new Intl.NumberFormat("ja-JP").format(totalPrice) }}円
                    </td>
                    <td class="px-4 pt-4"></td>
                  </tr>
                  <tr class="py-1">
                    <td colspan="2" class="px-4"></td>
                    <td colspan="2" class="px-4">消費税（10%）</td>
                    <td class="px-4 text-right">
                      {{
                        new Intl.NumberFormat("ja-JP").format(
                          Math.round(totalPrice * 0.1)
                        )
                      }}円
                    </td>
                    <td class="px-4 pt-4"></td>
                  </tr>
                  <tr class="py-1">
                    <td colspan="2" class="px-4 py-1"></td>
                    <td
                      colspan="2"
                      class="border-t-2 border-gray-300 pt-2 px-4 py-1"
                    >
                      合計（税込み）
                    </td>
                    <td
                      class="border-t-2 border-gray-300 pt-2 px-4 py-1 text-right"
                    >
                      {{
                        new Intl.NumberFormat("ja-JP").format(
                          totalPrice + Math.round(totalPrice * 0.1)
                        )
                      }}円
                    </td>
                    <td class="px-4 pt-4 border-t-2 border-gray-300"></td>
                  </tr>
                </tbody>
              </table>
              <form @submit.prevent="submit">
                <div
                  class="flex justify-end mt-2 whitespace-no-wrap text-sm md:text-base"
                >
                  <button
                    class="flex ml-auto text-white bg-indigo-500 border-0 py-1 px-6 focus:outline-none hover:bg-indigo-600 rounded"
                  >
                    支払いする
                  </button>
                </div>
              </form>
            </section>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
