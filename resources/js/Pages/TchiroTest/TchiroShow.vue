<script setup>
import { Head, router } from "@inertiajs/vue3";

defineProps({
  id: String,
  data: Object,
});

const deleteConfirm = (id) => {
  //router.postにするとshowになる。deleteにする
  router.delete(`/tchiro-test/${id}`, {
    onBefore: () => confirm("本当に削除しますか"),
  });
};
</script>
<template>
  <Head title="tchiroTest"> </Head>

  <div>hello.名前付きルートです</div>
  <div>ID: {{ id }}</div>
  <div>Title: {{ data.title }}</div>
  <div>Content: {{ data.content }}</div>
  <button @click="deleteConfirm(data.id)">削除する</button>
</template>
