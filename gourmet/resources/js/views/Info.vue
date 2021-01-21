<template>
  <div id="info">
    店舗についての概要を表示
  </div>
</template>

<script>
export default {
  name: "Info",
  data() {
    return {
      shopId: this.$route.params.id
    };
  }
};
</script>

<style scoped>
</style>
