<script setup>
import { router } from "@inertiajs/vue3";
import { reactive } from "vue";
//refで書くと以下
//import { ref } from "vue";
// const title = ref("");
// const content = ref("");

// objectにしたらreactive v-modelの形に注意
const form = reactive({
  title: null,
  content: null,
});

// validationをstoreにかけておくと、エラーがあった場合、errorsの形でcreateに戻る。
defineProps({
  errors: Object,
});

//functionの書き方。javascriptは変数の中に関数が入る。その場合、アロー関数を使ってconst name =()=>{}とする
//router.postはstoreのurl（route）を指定する。第二引数で渡したいデータ

const submitFunction = () => {
  router.post("/tchiro-test", form);
};
</script>
<template>
  <div>送信</div>
  <form @submit.prevent="submitFunction">
    <div class="text-red-500" v-if="errors.title">{{ errors.title }}</div>
    <input type="text" name="title" v-model="form.title" />{{ form.title
    }}<br />
    <div class="text-red-500" v-if="errors.content">{{ errors.content }}</div>

    <input type="text" name="content" v-model="form.content" />{{ form.content
    }}<br />
    <button>送信</button>
  </form>

  <div class="mb-8"></div>
</template>
