<template>
  <div class="reserved">
    {{ reservation }}
    予約完了
    メールにて予約内容をご確認ください。。
  </div>
</template>

<script>
export default {
  name: "Reserved",
  props: {
    reservation: {
      type: Object,
      required: true
    }
  }
}
</script>
<style scoped>
</style>
