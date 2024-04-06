<script setup>
import { defineProps, defineEmits } from "vue";

defineProps(["prop_sort", "constant_sortorder"]);
const emit = defineEmits(["emitSubmit"]);
const emitSubmit = (e) => {
  emit("emitSubmit", e.target.value);
};
</script>
<template>
  <div class="flex sm:flex-col flex-row mt-2 sm:mt-0">
    <span class="text-sm sm:mr-0 mr-2 mb-1 block">表示順</span>
    <select
      name="sort"
      id=""
      class="leading-5 px-2 text-sm"
      @change="emitSubmit"
    >
      <option name="sort" value="recommend" :selected="sort == 'recommend'">
        おすすめ順
      </option>
      <option
        name="sort"
        value="higherPrice"
        :selected="prop_sort == 'higherPrice'"
      >
        価格が高い順
      </option>
      <option
        name="sort"
        value="lowerPrice"
        :selected="prop_sort == 'lowerPrice'"
      >
        価格が安い順
      </option>
      <option name="sort" value="later" :selected="prop_sort == 'later'">
        新しい順
      </option>
      <option name="sort" value="older" :selected="prop_sort == 'older'">
        古い順
      </option>
    </select>
  </div>
</template>
