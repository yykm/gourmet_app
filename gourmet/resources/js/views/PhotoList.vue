<template>
  <div id="photoList">
    {{ this.shopId }}
  </div>
</template>

<script>
export default {
  name: "PhotoList",
  data() {
    return {
      shopId: this.$route.params.id
    };
  },
};
</script>

<style scoped>
</style>
