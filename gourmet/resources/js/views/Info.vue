<template>
  <!-- 店舗情報画面 -->
  <div class="info m-3 mt-5">
    <div class="info__inner w-100 h-75 bg-secondary text-white-50">
      <p class="m-auto">店舗詳細の実装は省略</p>
    </div>
  </div>
</template>

<script>
export default {
  name: "Info",
  data() {
    return {
      shop_id: this.$route.params.shop_id,
    };
  },
};
</script>

<style scoped>
.info {
  height: 500px;
}

.info__inner {
  position: relative;
}

.info__inner p {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -webkit-transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  font-size: 2rem;
}
</style>
