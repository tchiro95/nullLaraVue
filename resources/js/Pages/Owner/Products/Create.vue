<script setup>
import AuthenticatedLayout from "@/Layouts/OwnerAuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
// import ShowImage from "@/Components/ShowImage.vue";
import MicroModalComponent from "@/Components/MicroModalComponent.vue";
import { Head, useForm } from "@inertiajs/vue3";

const props = defineProps({
  shops: Array,
  images: Array,
  categories: Array,
});
const form = useForm({
  name: "",
  shop_id: props.shops[0].id,
  category: null,
  information: "",
  image1: null,
  image2: null,
  image3: null,
  image4: null,
  is_selling: 1,
  price: null,
  quantity: null,
  sort_order: null,
});

const submit = () => {
  form.post(route("owner.products.store"));
};

const handleImageData = (data) => {
  if (data.name == "image1") {
    form.image1 = data.id;
  }
  if (data.name == "image2") {
    form.image2 = data.id;
  }
  if (data.name == "image3") {
    form.image3 = data.id;
  }
  if (data.name == "image4") {
    form.image4 = data.id;
  }
};
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Product Create" />
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        教材登録
      </h2>
    </template>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <section class="text-gray-600 body-font relative">
            <div class="container px-5 py-24 mx-auto">
              <div class="lg:w-1/2 md:w-2/3 mx-auto">
                <form @submit.prevent="submit">
                  <div class="p-2 w-full">
                    <div class="relative">
                      <label for="name" class="leading-7 text-sm text-gray-600"
                        >商品名 ※必須</label
                      >
                      <input
                        type="text"
                        id="name"
                        name="name"
                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                        v-model="form.name"
                        required
                        autofocus
                      />
                      <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                  </div>
                  <div class="p-2 w-full">
                    <div class="relative">
                      <label for="price" class="leading-7 text-sm text-gray-600"
                        >価格 未設定可</label
                      >
                      <div class="flex flex-row w-full">
                        <input
                          type="number"
                          id="price"
                          name="price"
                          class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out mr-1"
                          v-model="form.price"
                          required
                          autofocus
                        />
                        <div class="mt-auto">円</div>
                      </div>
                      <InputError class="mt-2" :message="form.errors.price" />
                    </div>
                  </div>
                  <div class="p-2 w-full">
                    <div class="relative">
                      <label for="stock" class="leading-7 text-sm text-gray-600"
                        >初期在庫 未設定可</label
                      >
                      <div class="flex flex-row w-full">
                        <input
                          type="number"
                          id="stock"
                          name="stock"
                          class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out mr-1"
                          v-model="form.quantity"
                          autofocus
                        />
                        <div class="mt-auto">個</div>
                      </div>
                      <InputError class="mt-2" :message="form.errors.price" />
                    </div>
                  </div>

                  <div class="p-2 w-full">
                    <div class="relative">
                      <div class="leading-7 text-sm text-gray-600">
                        ショップ名
                      </div>
                      <div
                        id="shopID"
                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                      >
                        {{ shops[0].name }}
                      </div>
                    </div>
                  </div>
                  <div class="p-2 w-full">
                    <div class="relative">
                      <label
                        for="is_selling"
                        class="leading-7 text-sm text-gray-600"
                        >販売ステータス ※必須</label
                      ><br />

                      <div class="flex flex-row leading-5">
                        <input
                          type="radio"
                          name="is_selling"
                          value="1"
                          class="mr-1"
                          v-model="form.is_selling"
                          :checked="form.is_selling == 1"
                        /><span class="mr-4">販売中</span>
                        <input
                          type="radio"
                          name="is_selling"
                          value="0"
                          v-model="form.is_selling"
                          class="mr-1"
                          :checked="form.is_selling !== 1"
                        /><span class="mr-4">停止中</span>
                      </div>
                      <InputError
                        class="mt-2"
                        :message="form.errors.is_selling"
                      />
                    </div>
                  </div>
                  <div class="p-2 w-full">
                    <div class="relative">
                      <label
                        for="sort_order"
                        class="leading-7 text-sm text-gray-600"
                        >ソート順 ※未設定可</label
                      >
                      <div class="flex flex-row w-full">
                        <input
                          type="number"
                          id="sort_order"
                          name="sort_order"
                          class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out mr-1"
                          v-model="form.sort_order"
                          autofocus
                        />
                      </div>
                      <InputError
                        class="mt-2"
                        :message="form.errors.sort_order"
                      />
                    </div>
                  </div>
                  <div class="p-2 w-full">
                    <div class="relative">
                      <label for="name" class="leading-7 text-sm text-gray-600"
                        >カテゴリー ※必須</label
                      ><br />
                      <select name="category" v-model="form.category" required>
                        <optgroup
                          v-for="category in props.categories"
                          :key="category.id"
                          :label="category.name"
                        >
                          <option
                            v-for="secondary in category.secondary"
                            :key="secondary.id"
                            name="category"
                            :value="secondary.id"
                          >
                            {{ secondary.name }}
                          </option>
                        </optgroup>
                      </select>
                    </div>
                    <InputError class="mt-2" :message="form.errors.category" />
                  </div>

                  <div class="p-2 w-full">
                    <div class="relative">
                      <label
                        for="information"
                        class="leading-7 text-sm text-gray-600"
                        >商品情報 ※必須</label
                      >
                      <textarea
                        rows="3"
                        id="information"
                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                        v-model="form.information"
                        required
                        autofocus
                      />
                    </div>
                    <InputError
                      class="mt-2"
                      :message="form.errors.information"
                    />
                  </div>
                  <div class="p-2 w-full">
                    <div class="relative">
                      <MicroModalComponent
                        name="image1"
                        :images="props.images"
                        @emitImageData="handleImageData"
                        modalName="modal1"
                      ></MicroModalComponent>
                      <MicroModalComponent
                        name="image2"
                        :images="props.images"
                        @emitImageData="handleImageData"
                        modalName="modal1"
                      ></MicroModalComponent>
                      <MicroModalComponent
                        name="image3"
                        :images="props.images"
                        @emitImageData="handleImageData"
                        modalName="modal1"
                      ></MicroModalComponent>
                      <MicroModalComponent
                        name="image4"
                        :images="props.images"
                        @emitImageData="handleImageData"
                        modalName="modal1"
                      ></MicroModalComponent>
                    </div>
                  </div>

                  <div class="p-2 w-full mt-2">
                    <button
                      type="submit"
                      class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg"
                    >
                      登録する
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
