<script setup>
import AuthenticatedLayout from "@/Layouts/OwnerAuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
// import ShowImage from "@/Components/ShowImage.vue";
import MicroModalComponent from "@/Components/MicroModalComponent.vue";
import { Head, useForm, router } from "@inertiajs/vue3";

const props = defineProps({
  shops: Array,
  images: Array,
  categories: Array,
  product: Object,
  quantity: String,
});
const setStock = props.quantity == 0 ? null : props.quantity;
const form = useForm({
  name: props.product.name,
  shop_id: props.product.shop_id,
  category: props.product.secondary_category_id,
  information: props.product.information,
  image1: props.product.image1,
  image2: props.product.image2,
  image3: props.product.image3,
  image4: props.product.image4,
  is_selling: props.product.is_selling,
  price: props.product.price,
  quantity: null,
  stockType: 1,
  sort_order: props.product.sort_order,
  current_quantity: setStock,
});

const watchStocks = () => {
  if (form.quantity != null || form.quantity != 0) {
    const current = Number(form.current_quantity);
    if (form.stockType == 2) {
      if (current - form.quantity < 0) {
        form.errors.quantity = "マイナス在庫は不可です";
      }
    }
  }
};

const submit = () => {
  form.patch(route("owner.products.update", { id: props.product.id }));
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

const showalert = (productId) => {
  if (confirm("ID: " + productId + "を本当に消してもいいですか。")) {
    router.delete(route("owner.products.destroy", { id: productId }));
  }
};
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Product Update" />
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        教材情報更新
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
                        autocomplete="name"
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
                      <span class="leading-7 text-sm text-gray-600"
                        >在庫 整数を入力</span
                      >
                      <div class="flex flex-row w-full leading-8">
                        <span
                          v-if="quantity == null || quantity == 0"
                          class="text-sm text-gray-800 mr-4 my-auto"
                          >現在：0個(未設定)</span
                        >
                        <span v-else class="text-sm text-gray-800 mr-4 my-auto"
                          >現在：{{ form.current_quantity }}個</span
                        >
                        <input
                          type="number"
                          id="stock"
                          name="stock"
                          class="w-16 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 transition-colors duration-200 ease-in-out mr-1 my-auto"
                          v-model="form.quantity"
                          autofocus
                          @change="watchStocks"
                        />
                        <div class="my-auto">個</div>
                        <input
                          type="radio"
                          name="type"
                          value="1"
                          class="mr-1 ml-4 my-auto"
                          v-model="form.stockType"
                          @change="watchStocks"
                        /><span class="mr-4 my-auto">追加</span>
                        <input
                          type="radio"
                          name="type"
                          value="2"
                          v-model="form.stockType"
                          class="mr-1 my-auto"
                          @change="watchStocks"
                        /><span class="mr-4 my-auto">削減</span>
                      </div>
                      <InputError
                        class="mt-2"
                        :message="form.errors.quantity"
                      />
                      <InputError
                        class="mt-2"
                        :message="form.errors.current_quantity"
                      />
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
                      <span class="leading-7 text-sm text-gray-600"
                        >販売ステータス ※必須</span
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
                            :selected="
                              secondary.id === form.secondary_category_id
                            "
                          >
                            {{ secondary.name }}
                          </option>
                        </optgroup>
                      </select>
                    </div>
                    <InputError
                      class="mt-2"
                      :message="form.errors.secondary_category_id"
                    />
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
                        :imageId="props.product.image1"
                        @emitImageData="handleImageData"
                        modalName="modal1"
                      ></MicroModalComponent>
                      <MicroModalComponent
                        name="image2"
                        :images="props.images"
                        :imageId="props.product.image2"
                        @emitImageData="handleImageData"
                        modalName="modal1"
                      ></MicroModalComponent>
                      <MicroModalComponent
                        name="image3"
                        :images="props.images"
                        :imageId="props.product.image3"
                        @emitImageData="handleImageData"
                        modalName="modal1"
                      ></MicroModalComponent>
                      <MicroModalComponent
                        name="image4"
                        :images="props.images"
                        :imageId="props.product.image4"
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
                      更新する
                    </button>
                  </div>
                  <div class="p-2 w-full mt-6">
                    <button
                      type="button"
                      @click="showalert(props.product.id)"
                      class="flex mx-auto text-white bg-red-500 border-0 py-2 px-8 focus:outline-none hover:bg-red-600 rounded text-lg"
                    >
                      削除する
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
