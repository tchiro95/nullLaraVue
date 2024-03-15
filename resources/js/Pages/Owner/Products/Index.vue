<script setup>
import AuthenticatedLayout from "@/Layouts/OwnerAuthenticatedLayout.vue";
import ShowImage from "@/Components/ShowImage.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
  ownerInfo: Array,
  products: Array,
});
</script>

<template>
  <Head title="Products" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        教材情報
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
          <div class="flex pl-4 mt-4 mb-2 lg:w-2/3 w-full mx-auto">
            <Link
              class="flex ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded"
              :href="route('owner.products.create')"
            >
              新規登録
            </Link>
          </div>

          <div class="p-6 text-gray-900">
            <div class="sm:flex sm:flex-wrap">
              <div
                class="w-2/3 sm:w-1/4 p-2 sm:p-4"
                v-for="product in products"
                :key="product.id"
              >
                <Link :href="route('owner.products.edit', { id: product.id })">
                  <div class="border rounded-md p-2">
                    <ShowImage
                      v-if="product.image_first && product.image_first.filename"
                      :fileName="product.image_first.filename"
                      folderName="products"
                    ></ShowImage>
                    <ShowImage
                      v-else
                      fileName=""
                      folderName="products"
                    ></ShowImage>
                    <div class="text-sm text-center">{{ product.name }}</div>
                  </div>
                </Link>
              </div>
            </div>
          </div>
        </div>
        <!-- ページネーションのリンク -->
        <!-- <Pagination class="mt-6" :links="products.links"></Pagination> -->
      </div>
    </div>
  </AuthenticatedLayout>
</template>
