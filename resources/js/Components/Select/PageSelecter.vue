<script setup>
import { defineProps, defineEmits } from "vue";

defineProps(["request_amount"]);
const emit = defineEmits(["emitSubmitPage"]);
const emitSubmit = (e) => {
  emit("emitSubmitPage", e.target.value);
};
const amountArray = ["10", "25", "50", "100", "200"];
</script>
<template>
  <div class="flex sm:flex-col flex-row mt-2 sm:mt-0">
    <span class="text-sm sm:mr-0 mr-2 mb-1 block">表示件数</span>
    <select
      name="pagination"
      class="leading-5 px-2 text-sm"
      @change="emitSubmit"
    >
      <option
        v-for="n in amountArray"
        :key="n"
        :selected="request_amount == n"
        :value="n"
      >
        {{ n }}件
      </option>
    </select>
  </div>
</template>
