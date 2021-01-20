<template>
  <div id="review">
    {{ this.shopId }}
  </div>
</template>

<script>
export default {
  name: "Review",
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
  }
};
</script>

<style scoped>
</style>
