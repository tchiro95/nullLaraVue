<script setup>
import AuthenticatedLayout from "@/Layouts/OwnerAuthenticatedLayout.vue";
import ShowImage from "@/Components/ShowImage.vue";
import Pagination from "@/Components/Pagination.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
  images: Array,
});
</script>

<template>
  <Head title="Shops" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        画像情報
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
              :href="route('owner.images.create')"
            >
              新規登録
            </Link>
          </div>

          <div class="p-6 text-gray-900">
            <div class="sm:flex sm:flex-wrap">
              <div
                class="w-2/3 sm:w-1/4 p-2 sm:p-4"
                v-for="image in images.data"
                :key="image.id"
              >
                <Link :href="route('owner.images.edit', { id: image.id })">
                  <div class="border rounded-md p-2">
                    <ShowImage
                      :fileName="image.filename"
                      folderName="products"
                    ></ShowImage>
                    <div v-if="image.title" class="text-sm text-gray-700">
                      {{ image.title }}
                    </div>
                  </div>
                </Link>
              </div>
            </div>
          </div>
        </div>
        <!-- ページネーションのリンク -->
        <Pagination class="mt-6" :links="images.links"></Pagination>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
