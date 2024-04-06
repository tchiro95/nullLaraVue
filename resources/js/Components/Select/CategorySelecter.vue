<script setup>
import { defineProps, defineEmits } from "vue";

defineProps(["categories", "selectedSecondary"]);
const emit = defineEmits(["emitSubmitCategories"]);
const emitSubmit = (e) => {
  emit("emitSubmitCategories", e.target.value);
};
</script>
<template>
  <div class="flex sm:flex-col flex-row mt-2 sm:mt-0 text-sm">
    <span class="text-sm sm:mr-0 mr-2 mb-1 block">カテゴリー</span>
    <select name="category" @change="emitSubmit">
      <option name="category" value="0" :selected="0 == selectedSecondary">
        全て
      </option>
      <optgroup
        v-for="category in categories"
        :key="category.id"
        :label="category.name"
      >
        <option
          v-for="secondary in category.secondary"
          :key="secondary.id"
          name="category"
          :value="secondary.id"
          :selected="secondary.id == selectedSecondary"
        >
          {{ secondary.name }}
        </option>
      </optgroup>
    </select>
  </div>
</template>
