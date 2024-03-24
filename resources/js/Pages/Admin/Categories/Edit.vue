<script setup>
import AuthenticatedLayout from "@/Layouts/AdminAuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import { ref } from "vue";
import draggable from "vuedraggable";

const props = defineProps({
  categories: Array,
  primaryCategoryName: String,
});
const form = useForm({
  primary_category_id: props.categories[0].primary_category_id,
  name: "",
  insertSortOrder: props.categories.length + 1,
});

const submit = () => {
  form.post(route("admin.categories.store"));
};

const categoryArray = ref([...props.categories]);

const deleteCategory = (categoryId) => {
  if (confirm("本当に削除してもいいですか。")) {
    categoryArray.value = categoryArray.value.filter(
      (category) => category.id !== categoryId
    ); // valueとfilterを使って更新
    router.delete(route("admin.categories.destroy", { id: categoryId }));
  }
};

const updateform = useForm({
  categories: Array,
});
const saveOrder = () => {
  updateform.categories = categoryArray;
  updateform.patch(route("admin.categories.update", { id: 1 }));
};
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Shop Update" />
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        カテゴリー編集
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

          <section class="text-gray-600 body-font relative">
            <div class="container px-5 py-24 mx-auto">
              <div class="lg:w-1/2 md:w-2/3 mx-auto">
                <div class="p-2">
                  <div class="text-xl text-bold text-gray-800 mb-2">
                    {{ props.primaryCategoryName }}
                  </div>
                  <p>
                    カテゴリーの順番を入れ替える場合はドラッグしてください。
                  </p>
                  <div class="relative">
                    <draggable v-model="categoryArray" item-key="id">
                      <template #item="{ element, index }">
                        <div class="flex flex-row my-auto">
                          <div class="w-8 text-left py-1 my-auto">
                            {{ index + 1 }}:
                          </div>
                          <div
                            :id="element.id"
                            class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out mb-2"
                          >
                            {{ element.name }}
                          </div>
                          <div class="w-16 my-auto">
                            <button
                              class="flex ml-auto text-white bg-red-500 border-0 py-1 px-2 focus:outline-none hover:bg-red-600 rounded"
                              @click="deleteCategory(element.id)"
                            >
                              削除
                            </button>
                          </div>
                        </div>
                      </template>
                    </draggable>
                  </div>
                </div>
                <div class="p-2 w-full mt-2 mb-4">
                  <button
                    @click="saveOrder"
                    class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg"
                  >
                    並び順を保存する
                  </button>
                </div>
                <form @submit.prevent="submit">
                  <div class="p-2">
                    <div class="relative">
                      <label for="name" class="leading-7 text-sm text-gray-600"
                        >追加カテゴリー</label
                      >
                      <input
                        type="text"
                        id="name"
                        name="name"
                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                        v-model="form.name"
                        autofocus
                        autocomplete="name"
                      />
                      <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                  </div>

                  <div class="p-2 w-full mt-2">
                    <button
                      type="submit"
                      class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg"
                    >
                      カテゴリーを追加する
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
