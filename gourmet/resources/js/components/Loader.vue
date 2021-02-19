<template>
  <!-- ローダー -->
  <div class="loader">
    <b-spinner :style="{ width, height }" :label="label"></b-spinner>
  </div>
</template>

<script>
export default {
  name: "Loader",
  props: {
    // 横幅
    width: {
      type: String,
      required: false,
      default: "3rem",
    },
    // 縦幅
    height: {
      type: String,
      required: false,
      default: "3rem",
    },
    // ラベル
    label: {
      type: String,
      required: false,
      default: "spinner",
    },
  },
};
</script>

<style scoped></style>
