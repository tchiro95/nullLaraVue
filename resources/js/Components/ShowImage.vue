<script setup>
defineProps(["fileName", "folderName", "insertClass", "insertAlt"]);
</script>

<template>
  <div>
    <img
      :alt="insertAlt ? insertAlt : ''"
      :class="insertClass ? insertClass : ''"
      :src="
        fileName ? `/storage/${folderName}/${fileName}` : '/images/no_image.jpg'
      "
    />
  </div>
</template>
