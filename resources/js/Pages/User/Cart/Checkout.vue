<script setup>
import { defineProps, onMounted } from "vue";

const props = defineProps({
  publicKey: String,
  session: Object,
});

let stripe;

onMounted(async () => {
  // Stripeのスクリプトをロードしてから実行
  await loadStripe();

  stripe
    .redirectToCheckout({
      sessionId: props.session.id,
    })
    .then(function (result) {
      // Inertiaのページ遷移機能を使用して、URLを指定
      window.location.href = route("user.cart.cancel");
    });
});

async function loadStripe() {
  if (!window.Stripe) {
    const stripeScript = document.createElement("script");
    stripeScript.src = "https://js.stripe.com/v3/";
    document.head.appendChild(stripeScript);
    await new Promise((resolve) => {
      stripeScript.onload = resolve;
    });
  }
  stripe = Stripe(props.publicKey);
}
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        決済ページリダイレクト
      </h2>
    </template>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div
            v-if="$page.props.flash.status === 'message'"
            class="p-2 bg-blue-300"
          >
            {{ $page.props.flash.message }}
          </div>
          <div
            v-if="$page.props.flash.status === 'alert'"
            class="p-2 bg-red-300"
          >
            {{ $page.props.flash.message }}
          </div>
          <div class="p-6 text-gray-900">
            <p>決済ページへリダイレクトします。</p>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
