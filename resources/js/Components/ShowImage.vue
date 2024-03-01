<script setup>
defineProps(["fileName", "folderName"]);
</script>

<template>
  <div>
    <img
      :src="
        fileName ? `/storage/${folderName}/${fileName}` : '/images/no_image.jpg'
      "
    />
  </div>
</template>
