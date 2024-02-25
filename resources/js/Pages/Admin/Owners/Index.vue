<script setup>
import AuthenticatedLayout from "@/Layouts/AdminAuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import { Head, Link, router } from "@inertiajs/vue3";
// import { onMounted } from "vue";
//日付はmomentというライブラリで整理する
import dayjs from "dayjs";
dayjs.locale("ja");

defineProps({
  owners: Object,
});

const showalert = (ownerId) => {
  if (confirm("ID: " + ownerId + "を本当に消してもいいですか。")) {
    router.delete(route("admin.owners.destroy", { id: ownerId }));
  }
};
</script>

<template>
  <Head title="Owners Index" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        オーナー一覧
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

          <section class="text-gray-600 body-font">
            <div class="container px-5 py-12 mx-auto">
              <div class="flex pl-4 mt-4 mb-2 lg:w-2/3 w-full mx-auto">
                <Link
                  class="flex ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded"
                  :href="route('admin.owners.create')"
                >
                  新規登録
                </Link>
              </div>

              <div class="lg:w-2/3 w-full mx-auto overflow-auto">
                <table class="table-auto w-full text-left whitespace-no-wrap">
                  <thead>
                    <tr>
                      <th
                        class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl"
                      >
                        氏名
                      </th>
                      <th
                        class="md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"
                      >
                        メールアドレス
                      </th>
                      <th
                        class="md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"
                      >
                        作成日
                      </th>
                      <th
                        class="md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"
                      >
                        編集
                      </th>
                      <th
                        class="md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"
                      >
                        削除
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="owner in owners.data" :key="owner.id">
                      <td class="px-4 py-3">{{ owner.name }}</td>
                      <td class="md:px-4 py-3">{{ owner.email }}</td>
                      <td class="md:px-4 py-3">
                        {{
                          dayjs(owner.created_at).format(
                            "YYYY年M月D日 H時m分s秒"
                          )
                        }}
                      </td>
                      <td class="md:px-4 py-3">
                        <div class="flex">
                          <Link
                            :href="route('admin.owners.edit', { id: owner.id })"
                            class="flex text-white bg-indigo-500 border-0 md:py-1 px-2 focus:outline-none hover:bg-indigo-600 rounded"
                          >
                            編集
                          </Link>
                        </div>
                      </td>
                      <td class="md:px-4 py-3">
                        <div class="flex">
                          <Link
                            @click="showalert(owner.id)"
                            class="flex text-white bg-red-500 border-0 md:py-1 px-2 focus:outline-none hover:bg-red-600 rounded"
                          >
                            削除
                          </Link>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <!-- ページネーションのリンク -->
                <Pagination class="mt-6" :links="owners.links"></Pagination>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
