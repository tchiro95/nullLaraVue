<script setup>
import { ref, defineProps } from "vue";
const isShow = ref(false);
const toggleStatus = () => {
  isShow.value = !isShow.value;
  if (isShow.value) {
    document.body.style.overflow = "hidden";
  } else {
    document.body.style.overflow = null;
  }
};
defineProps(["shop"]);
</script>
<template>
  <div
    class="mt-3 flex text-white bg-gray-500 border-0 py-1 px-2 focus:outline-none hover:bg-gray-600 rounded w-20"
    @click="toggleStatus"
  >
    詳細情報
  </div>

  <div v-show="isShow" class="modal" id="modalShop">
    <div class="modal__overlay" tabindex="-1">
      <div
        class="modal__container w-2/3 p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modalShop + '-title'"
      >
        <header class="modal__header">
          <h2 class="text-xl text-gray-700 font-bold" id="modalShop + '-title'">
            {{ shop.name }}
          </h2>
          <button
            @click="toggleStatus"
            type="button"
            class="modal__close"
            aria-label="Close modal"
          ></button>
        </header>
        <main class="modal__content" id="modalShop-content'">
          <h3 class="font-bold text-gray-700">店舗情報</h3>
          <div class="w-full flex flex-row flex-wrap p-2">
            {{ shop.information }}
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
</template>
