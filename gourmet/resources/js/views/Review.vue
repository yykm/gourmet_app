<template>
  <div id="review">
    <ReviewForm :shopId="shopId" />
  </div>
</template>

<script>
import ReviewForm from "./../components/ReviewForm.vue";

export default {
  name: "Review",
  components: {
    ReviewForm
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
