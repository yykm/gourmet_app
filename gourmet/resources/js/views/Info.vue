<template>
  <div id="info">
    {{ this.shopId }}
  </div>
</template>

<script>
export default {
  name: "Info",
  props: {
    // 店舗ID
    id: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      shopId: this.$route.params.id
    };
  },
};
</script>

<style scoped>
</style>
