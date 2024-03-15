<script setup>
import { ref } from "vue";
import { defineProps, defineEmits } from "vue";
const isShow = ref(false);
const toggleStatus = () => {
  isShow.value = !isShow.value;
};

const props = defineProps(["name", "images", "modalName", "imageId"]);
const emit = defineEmits(["emitImageData"]);

const imageData = {
  name: "",
  id: 0,
  filename: "",
  filepath: "",
};
if (props.imageId) {
  props.images.forEach((image) => {
    if (image.id === props.imageId) {
      imageData.name = image.name;
      imageData.id = image.id;
      imageData.filename = image.filename;
      imageData.filepath = "/storage/products/" + image.filename;
    }
  });
}

const emitImageData = (data) => {
  imageData.name = data.name;
  imageData.id = data.id;
  imageData.filename = data.file;
  imageData.filepath = data.path;
  emit("emitImageData", imageData);
  toggleStatus();
};
</script>

<template>
  <div v-show="isShow" class="modal" :id="modalName">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      <div
        class="modal__container"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modalName + '-title'"
      >
        <header class="modal__header">
          <h2 class="text-xl text-gray-700" :id="modalName + '-title'">
            ファイルを選択してください
          </h2>
          <button
            @click="toggleStatus"
            type="button"
            class="modal__close"
            aria-label="Close modal"
          ></button>
        </header>
        <main class="modal__content" :id="modalName + '-content'">
          <div class="w-full flex flex-row flex-wrap p-2">
            <div
              v-for="image in images"
              :key="image.id"
              class="mx-1 border rounded-md p-2 w-1/4"
              @click="
                emitImageData({
                  name: name,
                  image: '/storage/products/' + image.filename,
                  id: image.id,
                  file: image.filename,
                  path: '/storage/products/' + image.filename,
                })
              "
            >
              <img :src="`/storage/products/${image.filename}`" />
            </div>
          </div>
        </main>
        <footer class="modal__footer">
          <button
            @click="toggleStatus"
            type="button"
            class="modal__btn"
            aria-label="閉じる"
          >
            閉じる
          </button>
        </footer>
      </div>
    </div>
  </div>
  <div class="flex justify-between my-auto w-full sm:flex-row flex-col">
    <div
      class="text-left text-sm sm:mb-2 mb-1 text-blue-600 cursor-pointer py-1"
      @click="toggleStatus"
    >
      <span v-show="name == 'image1'"> １枚目のファイルを選択 </span>
      <span v-show="name == 'image2'"> ２枚目のファイルを選択 </span>
      <span v-show="name == 'image3'"> ３枚目のファイルを選択 </span>
      <span v-show="name == 'image4'"> ４枚目のファイルを選択 </span>
    </div>
    <div
      v-show="imageData.filepath"
      class="md:w-1/4 w-1/2 text-left sm:text-right py-1 mb-2"
    >
      <img :id="imageData.id" :src="imageData.filepath" />
    </div>
  </div>
</template>
