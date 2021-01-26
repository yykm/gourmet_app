<template>
  <div class="loader">
    <b-spinner
      :style="{ width, height }"
      :label="label"
    ></b-spinner>
  </div>
</template>

<script>
export default {
  name: "Loader",
  props: {
    width: {
      type: String,
      required: false,
      default: "3rem"
    },
    height: {
      type: String,
      required: false,
      default: "3rem"
    },
    label: {
      type: String,
      required: false,
      default: "spinner"
    }
  }
};
</script>

<style scoped></style>
