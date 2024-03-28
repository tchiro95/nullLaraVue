<script setup>
import { defineEmits, ref } from "vue";

const searchWord = ref(""); // リアクティブ変数として定義

const props = defineProps(["search_word"]);
const inputword = ref(props.search_word !== "" ? props.search_word : "");
const emit = defineEmits(["emitSubmitInput", "emitInput"]);
const setWord = () => {
  emit("emitInput", inputword.value);
};
const emitSubmit = () => {
  emit("emitSubmitInput", inputword.value);
};
</script>
<template>
  <div class="flex sm:flex-col flex-row mt-2 sm:mt-0">
    <span class="text-sm sm:mr-0 mr-2 mb-1 block">検索</span>
    <div class="flex flex-row">
      <input
        type="text"
        :placeholder="searchWord !== '' ? searchWord : 'キーワードを入力'"
        v-model="inputword"
        @blur="setWord"
        class="w-full"
      />
      <!-- エンター押してすぐに動かしたい場合はinputの中に以下をいれる_ただ変換でも動いてしまう@keyup.enter="emitSubmit" -->

      <button
        class="ml-4 mr-auto text-white bg-gray-500 border-0 py-1 px-4 focus:outline-none hover:bg-gray-600 rounded text-sm w-32"
        @click="emitSubmit"
      >
        検索する
      </button>
    </div>
  </div>
</template>
