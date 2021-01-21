<template>
  <div id="review">
    {{ this.shopId }}b
  </div>
</template>

<script>
export default {
  name: "Review",
  data() {
    return {
      shopId: this.$route.params.id
    };
  }
};
</script>

<style scoped>
</style>
