<script setup>
import AuthenticatedLayout from "@/Layouts/AdminAuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
// import { onMounted } from "vue";

const props = defineProps({
  categories: Array,
});
</script>

<template>
  <Head title="Categories Index" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        カテゴリー一覧
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

          <section class="text-gray-600 body-font">
            <div class="container px-5 py-12 mx-auto">
              <div class="flex pl-4 mt-4 mb-2 lg:w-2/3 w-full mx-auto">
                <Link
                  class="flex ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded"
                  :href="route('admin.categories.create')"
                >
                  新規登録
                </Link>
              </div>

              <div class="lg:w-2/3 w-full mx-auto overflow-auto">
                <div class="container px-5 py-8 mx-auto">
                  <div class="flex flex-wrap md:text-left -mb-10 -mx-4">
                    <div
                      v-for="category in props.categories"
                      :key="category.id"
                      class="border border-gray-700 rounded-md mx-2 my-2"
                    >
                      <Link
                        :href="
                          route('admin.categories.edit', { id: category.id })
                        "
                      >
                        <div class="w-full px-4">
                          <h2
                            class="title-font font-medium text-gray-900 tracking-widest text-xl mb-3 text-left"
                          >
                            <span class="text-sm text-gray-800"
                              >カテゴリー名</span
                            ><br />
                            {{ category.name }}
                          </h2>

                          <nav class="list-none mb-2">
                            <div
                              v-for="secondary in category.secondary"
                              :key="secondary.id"
                              :draggable="true"
                              @dragstart="dragStart(category, $event)"
                              @dragover.prevent
                              @drop="drop(category, $event)"
                            >
                              <li class="text-left">
                                <a class="text-gray-600 hover:text-gray-800"
                                  >{{ secondary.sort_order }}:
                                  {{ secondary.name }}</a
                                >
                              </li>
                            </div>
                          </nav>
                        </div>
                      </Link>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
