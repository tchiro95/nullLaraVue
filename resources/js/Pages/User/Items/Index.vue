<script setup>
import AuthenticatedLayout from "@/Layouts/UserAuthenticatedLayout.vue";
import ShowImage from "@/Components/ShowImage.vue";
import Pagination from "@/Components/Pagination.vue";
import SortSelecter from "@/Components/Select/SortSelecter.vue";
import PageSelecter from "@/Components/Select/PageSelecter.vue";
import CategorySelecter from "@/Components/Select/CategorySelecter.vue";
import InputSearch from "@/Components/Inputbox/InputSearch.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
  products: Object,
  sort: String,
  constant_sortorder: Object,
  pagination: String,
  searchword: String,
  categories: Array,
  selectedSecondary: Number,
});

const form = useForm(
  {
    sort: props.sort,
    pagination: props.pagination,
    searchword: props.searchword,
    secondary: props.selectedSecondary,
  },
  { preserveState: true }
);

const setPagination = (data) => {
  form.pagination = data;
  submitForm();
};
const setSearchInput = (data) => {
  form.searchword = data;
};

const setSortOrder = (data) => {
  form.sort = data;
  submitForm();
};
const setCategories = (data) => {
  form.secondary = data;
};

const searchbtn = (data) => {
  form.searchword = data;
  submitForm();
};

const submitForm = () => {
  form.get(route("user.items.index"), {
    data: {
      sort: form.sort,
      pagination: form.pagination,
      searchword: form.searchword,
      secondary: form.secondary,
    },
  });
};
</script>

<template>
  <Head title="Products" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight mr-6">
        教材情報
      </h2>
      <form @submit.prevent="submit">
        <div class="md:flex justify-between items-center mt-2 text-gray-800">
          <!-- コンポーネント -->
          <div class="flex flex-col md:flex-row">
            <CategorySelecter
              :categories="categories"
              :selectedSecondary="selectedSecondary"
              @emit-submit-categories="setCategories"
              class="mr-4"
            ></CategorySelecter>
            <InputSearch
              @emit-submit-input="searchbtn"
              @emit-input="setSearchInput"
              :search_word="searchword"
              class="mr-4"
            ></InputSearch>
          </div>
          <div class="flex flex-row">
            <SortSelecter
              :constant_sortorder="constant_sortorder"
              :prop_sort="form.sort"
              @emit-submit="setSortOrder"
              class="mr-4"
            ></SortSelecter>
            <!-- コンポーネント -->
            <PageSelecter
              :request_amount="form.pagination"
              @emit-submit-page="setPagination"
              class="mr-4"
            ></PageSelecter>
          </div>

          <!-- コンポーネント -->
        </div>
      </form>
    </template>

    <div class="py-12">
      <div class="masx-w-7xl mx-auto sm:px-6 lg:px-8">
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
          <section class="text-gray-600 body-font">
            <div class="container px-5 py-24 mx-auto">
              <div class="flex flex-col text-center w-full mb-20">
                <h1
                  class="text-2xl font-medium title-font mb-4 text-gray-900 tracking-widest"
                >
                  教材紹介
                </h1>
                <p class="lg:w-2/3 mx-auto leading-relaxed text-base">
                  教材を紹介します。クリックで詳細を参照できます。
                </p>
              </div>
              <div class="flex flex-wrap -m-4">
                <div
                  class="p-4 lg:w-1/3 md:w-1/2"
                  v-for="product in products.data"
                  :key="product.id"
                >
                  <div
                    class="h-full flex sm:flex-row flex-col items-center sm:justify-start justify-center text-center sm:text-left p-1 bg-emerald-100 rounded"
                  >
                    <Link
                      :href="route('user.items.show', { item: product.id })"
                    >
                      <ShowImage
                        v-if="product.filename"
                        insertAlt="team"
                        insertClass="flex-shrink-0 rounded-lg w-80 h-48 object-cover object-center sm:mb-0 mb-4"
                        :fileName="product.filename"
                        folderName="products"
                      ></ShowImage>
                      <ShowImage
                        v-else
                        insertAlt="noimage"
                        insertClass="flex-shrink-0 rounded-lg w-48 h-48 object-cover object-center sm:mb-0 mb-4"
                        fileName=""
                        folderName="products"
                      ></ShowImage>
                      <div class="flex-grow sm:pl-8">
                        <h3 class="text-gray-500 mb-3 text-sm">
                          {{ product.primaryname }}:
                          {{ product.category }}
                        </h3>
                        <h2
                          class="title-font font-medium text-lg text-gray-900"
                        >
                          {{ product.name }}
                        </h2>
                        <p class="mb-2">
                          {{
                            product.information.length > 50
                              ? product.information.slice(0, 50) + "..."
                              : product.information
                          }}
                        </p>
                        <p v-if="product.price" class="mb-2">
                          {{ product.price }}円
                        </p>
                        <span class="inline-flex">
                          <a class="text-gray-500">
                            <svg
                              fill="none"
                              stroke="currentColor"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              class="w-5 h-5"
                              viewBox="0 0 24 24"
                            >
                              <path
                                d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"
                              ></path>
                            </svg>
                          </a>
                          <a class="ml-2 text-gray-500">
                            <svg
                              fill="none"
                              stroke="currentColor"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              class="w-5 h-5"
                              viewBox="0 0 24 24"
                            >
                              <path
                                d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"
                              ></path>
                            </svg>
                          </a>
                          <a class="ml-2 text-gray-500">
                            <svg
                              fill="none"
                              stroke="currentColor"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              class="w-5 h-5"
                              viewBox="0 0 24 24"
                            >
                              <path
                                d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"
                              ></path>
                            </svg>
                          </a>
                        </span>
                      </div>
                    </Link>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
        <!-- ページネーションのリンク -->
        <Pagination class="mt-6" :links="products.links"></Pagination>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
