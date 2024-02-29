<script setup>
import AuthenticatedLayout from "@/Layouts/OwnerAuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, useForm } from "@inertiajs/vue3";

const props = defineProps({
  shop: Object,
});

const form = useForm({
  name: props.shop.name,
  information: props.shop.information,
  //fileuploadはinertiaのfile upload マニュアルを参照
  image: null,
  is_selling: props.shop.is_selling,
});

const submit = () => {
  console.log(form);
  form.post(route("owner.shops.update", { id: props.shop.id }));
};
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Shop Update" />
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        店舗情報編集
      </h2>
    </template>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <section class="text-gray-600 body-font relative">
            <div class="container px-5 py-24 mx-auto">
              <div class="lg:w-1/2 md:w-2/3 mx-auto">
                <form @submit.prevent="submit" enctype="multipart/form-data">
                  <div class="p-2">
                    <div class="relative">
                      <label for="name" class="leading-7 text-sm text-gray-600"
                        >店舗名</label
                      >
                      <input
                        type="text"
                        id="name"
                        name="name"
                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                      />
                      <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                  </div>
                  <div class="p-2 w-full">
                    <div class="relative">
                      <label
                        for="form.owner_name"
                        class="leading-7 text-sm text-gray-600"
                        >オーナー名</label
                      >
                      <div
                        type="text"
                        id="owner_name"
                        name="owner_name"
                        class="w-full bg-gray-100 bg-opacity-50 rounded text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out border-r-0"
                      >
                        {{ props.shop.owner.name }}
                      </div>
                    </div>
                  </div>
                  <div class="p-2 w-full">
                    <div class="relative">
                      <label
                        for="information"
                        class="leading-7 text-sm text-gray-600"
                        >情報</label
                      >
                      <textarea
                        rows="3"
                        id="information"
                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                        v-model="form.information"
                        required
                        autofocus
                        autocomplete="information"
                      />
                    </div>
                  </div>
                  <div class="p-2">
                    <div class="relative">
                      <label for="name" class="leading-7 text-sm text-gray-600"
                        >画像</label
                      >
                      <input
                        type="file"
                        accept="image/png,image/jpeg,image/jpg"
                        @input="form.image = $event.target.files[0]"
                        id="image"
                        name="image"
                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                      />
                      <InputError
                        class="mt-2"
                        :message="form.errors.filename"
                      />
                      <progress
                        v-if="form.progress"
                        :value="form.progress.percentage"
                        max="100"
                      >
                        {{ form.progress.percentage }}%
                      </progress>
                    </div>
                  </div>

                  <div class="p-2 w-full mt-2">
                    <button
                      type="submit"
                      class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg"
                    >
                      更新する
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
