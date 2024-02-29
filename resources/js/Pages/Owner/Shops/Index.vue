<script setup>
import AuthenticatedLayout from "@/Layouts/OwnerAuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
// import { onMounted } from "vue";
//日付はmomentというライブラリで整理する
import dayjs from "dayjs";
dayjs.locale("ja");

defineProps({
  shops: Object,
});
</script>

<template>
  <Head title="Shops" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        店舗情報
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <div class="w-1/2 p-4" v-for="shop in shops" :key="shop.id">
              <Link :href="route('owner.shops.edit', { id: shop.id })">
                <div class="border rounded-md p-4">
                  <div class="mb-4">
                    <span
                      v-if="shop.is_selling"
                      class="border p-2 rounded-md bg-blue-400 text-white"
                      >販売中</span
                    >
                    <span
                      v-if="!shop.is_selling"
                      class="border p-2 rounded-md bg-red-400 text-white"
                      >停止中</span
                    >
                  </div>
                  <div class="text-xl">{{ shop.name }}</div>
                  <div>
                    <img
                      :src="
                        shop.filename
                          ? `/storage/${shop.filename}.jpg`
                          : '/images/no_image.jpg'
                      "
                    />
                  </div>
                </div>
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
