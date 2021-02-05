<template>
  <div class="reserved">
    <Header />

    {{ reservation }}
    予約完了 メールにて予約内容をご確認ください。。
  </div>
</template>

<script>
import Header from "./../components/Header.vue";

export default {
  name: "Reserved",
  components: {
    Header
  },
  props: {
    reservation: {
      type: Object,
      required: true
    }
  }
};
</script>
<style scoped>
</style>
