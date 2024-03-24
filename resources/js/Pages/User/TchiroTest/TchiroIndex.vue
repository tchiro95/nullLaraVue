<script setup>
import { Head, Link } from "@inertiajs/vue3";
defineProps({
  datas: Array,
});
</script>
<template>
  <Head title="tchiroTest"> </Head>

  <div v-if="$page.props.flash.message" class="text-blue-500">
    {{ $page.props.flash.message }}
  </div>
  <div>hello.名前付きルートです</div>
  <Link :href="route('tchirotest.show', { id: 1 })">#1</Link>
  <div class="mb-8"></div>

  <ul>
    <li v-for="data in datas" :key="data.id">
      <span>{{ data.title }}: {{ data.content }}</span>
      <span class="ml-4 text-blue-500"
        ><Link :href="route('tchirotest.show', { id: data.id })"
          >詳細</Link
        ></span
      >
    </li>
  </ul>
  <div class="mt-2 text-blue-500">
    <Link :href="route('tchirotest.create')">新規作成</Link>
  </div>
</template>
