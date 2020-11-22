<template>
  <div class="footer">
    <footer>
      <div class="text-center">
        <small
          ><a href="javascript:void(0);"
            >Copyright (C) {{ date }} {{ holder }} All Rights Reserved.</a
          ></small
        >
      </div>
    </footer>
  </div>
</template>

<script>
export default {
  name: 'Footer',
  data() {
    return {
      holder: 'holder',
      date: null
    };
  },
  mounted: function() {
    // 便宜的に初期化
    this.date = new Date().getFullYear();
  }
};
</script>

<style scoped>
/* フッター背景 */
.footer footer {
  padding: 10px;
  background: #ececec;
}
/* リンクの色 */
.footer a {
  color: #424242;
}
</style>
