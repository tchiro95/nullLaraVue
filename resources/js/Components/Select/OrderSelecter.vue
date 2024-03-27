<script setup>
import { defineProps, defineEmits } from "vue";

defineProps(["selected_order", "constant_sortorder"]);
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
      <option
        name="sort"
        value="recommend"
        :selected="
          selected_order == constant_sortorder['recommend'] ||
          selected_order == null
        "
      >
        おすすめ順
      </option>
      <option
        name="sort"
        value="higherPrice"
        :selected="selected_order == constant_sortorder['higherPrice']"
      >
        価格が高い順
      </option>
      <option
        name="sort"
        value="lowerPrice"
        :selected="selected_order == constant_sortorder['lowerPrice']"
      >
        価格が安い順
      </option>
      <option
        name="sort"
        value="later"
        :selected="selected_order == constant_sortorder['later']"
      >
        新しい順
      </option>
      <option
        name="sort"
        value="older"
        :selected="selected_order == constant_sortorder['older']"
      >
        古い順
      </option>
    </select>
  </div>
</template>
