<script setup>
import { defineProps, defineEmits, onMounted } from "vue";

const props = defineProps({
  categories: Array,
});

const emit = defineEmits(["update-categories"]);

onMounted(() => {
  emit("update-categories", props.categories);
});

const dragStart = (category, event) => {
  event.dataTransfer.setData("text/plain", category.id);
};

const drop = (targetCategory, event) => {
  const categoryId = event.dataTransfer.getData("text/plain");
  const draggedCategory = props.categories.find(
    (category) => category.id == categoryId
  );

  // ドロップ先のカテゴリーの sort_order を取得
  const targetSortOrder = targetCategory.sort_order;

  // ドラッグされたカテゴリーの sort_order を更新
  draggedCategory.sort_order = targetSortOrder;

  // ドロップ先のカテゴリーのインデックスを取得
  const targetIndex = props.categories.findIndex(
    (category) => category.id === targetCategory.id
  );

  // ドラッグされたカテゴリーのインデックスを取得
  const draggedIndex = props.categories.findIndex(
    (category) => category.id === categoryId
  );

  const updatedCategories = [...props.categories]; // ドロップ後のカテゴリーデータの配列

  // ドロップされたカテゴリーの直前または直後の要素の sort_order を更新
  if (draggedIndex < targetIndex) {
    for (let i = draggedIndex + 1; i <= targetIndex; i++) {
      updatedCategories[i].sort_order--;
    }
  } else {
    for (let i = targetIndex; i < draggedIndex; i++) {
      updatedCategories[i].sort_order++;
    }
  }

  emit("update-categories", updatedCategories);
  // ここでサーバーに変更を送信する処理を追加する
};
</script>
<template>
  <div
    v-for="category in categories"
    :key="category.id"
    :id="category.id"
    draggable="true"
    @dragstart="dragStart(category, $event)"
    @dragover.prevent
    @drop="drop(category, $event)"
  >
    <div
      id="{{category.id}}"
      class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out mb-2"
    >
      {{ category.sort_order }}: {{ category.name }}
    </div>
  </div>
</template>
