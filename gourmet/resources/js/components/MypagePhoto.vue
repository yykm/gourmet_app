<template>
  <div id="mypagePhoto">
    {{photos}}
  </div>
</template>

<script>
export default {
  name: "MypagePhoto",
  props: {
    photos: {
      type: Array,
      required: false,
    },
  },
};
</script>

<style scoped>
</style>
