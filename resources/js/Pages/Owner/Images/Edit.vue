<script setup>
import AuthenticatedLayout from "@/Layouts/OwnerAuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import ShowImage from "@/Components/ShowImage.vue";

const props = defineProps({
  image: Array,
});

const form = useForm({
  title: props.image.title,
  image: props.image.image,
});

const submit = () => {
  form.patch(route("owner.images.update", { id: props.image.id }));
};

const showalert = (imageId) => {
  if (confirm("ID: " + imageId + "を本当に消してもいいですか。")) {
    router.delete(route("owner.images.destroy", { id: imageId }));
  }
};

// const validateFileSize = (e) => {
//   const files = Array.from(e.target.files);
//   const maxSize = 2 * 1024 * 1024; // 2MBをバイト単位で表現
//   form.errors.image = "";

//   for (let i = 0; i < files.length; i++) {
//     const file = files[i];
//     if (file.size > maxSize) {
//       form.errors.image = "ファイルサイズはそれぞれ2MB以内にしてください。";
//       form.image = null;
//       break;
//     }
//     const allowedTypes = [
//       "image/jpeg",
//       "image/jpg",
//       "image/png",
//       "image/ping",
//       "image/gif",
//       "image/webp",
//     ];
//     if (!allowedTypes.includes(file.type)) {
//       form.errors.image = "ファイルは画像ファイルである必要があります";
//       form.image = null;
//       break;
//     }
//   }
//   form.image = files;
// };
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Image Edit" />
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        画像情報編集
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
                      <div class="border rounded-md p-2">
                        <ShowImage
                          :fileName="props.image.filename"
                          folderName="products"
                        ></ShowImage>
                      </div>
                    </div>
                  </div>
                  <div class="p-2">
                    <div class="relative">
                      <label for="title" class="leading-7 text-sm text-gray-600"
                        >画像タイトル</label
                      >
                      <input
                        type="text"
                        id="title"
                        name="title"
                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                        v-model="form.title"
                        required
                        autofocus
                        autocomplete="title"
                      />
                      <InputError class="mt-2" :message="form.errors.title" />
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
                      @click="showalert(props.image.id)"
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
