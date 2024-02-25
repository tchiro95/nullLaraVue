<script setup>
import AuthenticatedLayout from "@/Layouts/AdminAuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, useForm } from "@inertiajs/vue3";

const props = defineProps({
  owner: Object,
});

const form = useForm({
  name: props.owner.name,
  email: props.owner.email,
  password: "",
  password_confirmation: "",
});

const submit = () => {
  form.patch(route("admin.owners.update", { id: props.owner.id }));
  // router.post("/admin/owners", form);
};
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Owners Register" />
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        オーナー編集
      </h2>
    </template>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <section class="text-gray-600 body-font relative">
            <div class="container px-5 py-24 mx-auto">
              <div class="lg:w-1/2 md:w-2/3 mx-auto">
                <form @submit.prevent="submit">
                  <div class="p-2">
                    <div class="relative">
                      <label for="name" class="leading-7 text-sm text-gray-600"
                        >Name</label
                      >
                      <input
                        type="text"
                        id="name"
                        name="name"
                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                      />
                      <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                  </div>
                  <div class="p-2 w-full">
                    <div class="relative">
                      <label for="email" class="leading-7 text-sm text-gray-600"
                        >Email</label
                      >
                      <input
                        type="text"
                        id="email"
                        name="email"
                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                      />

                      <InputError class="mt-2" :message="form.errors.email" />
                    </div>
                  </div>
                  <div class="p-2 w-full">
                    <div class="relative">
                      <label for="name" class="leading-7 text-sm text-gray-600"
                        >パスワード</label
                      >
                      <input
                        type="password"
                        id="password"
                        name="password"
                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                        v-model="form.password"
                        required
                        autofocus
                        autocomplete="new-password"
                      />
                      <InputError
                        class="mt-2"
                        :message="form.errors.password"
                      />
                    </div>
                  </div>
                  <div class="p-2 w-full">
                    <div class="relative">
                      <label for="name" class="leading-7 text-sm text-gray-600"
                        >パスワード確認</label
                      >
                      <input
                        type="password"
                        id="password_confirmation"
                        name="password"
                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                        v-model="form.password_confirmation"
                        required
                        autofocus
                        autocomplete="new-password"
                      />

                      <InputError
                        class="mt-2"
                        :message="form.errors.password_confirmation"
                      />
                    </div>
                  </div>
                  <div class="p-2 w-full mt-2">
                    <button
                      class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg"
                    >
                      更新する
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
