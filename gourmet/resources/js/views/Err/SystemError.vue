<template>
  <!-- 500 System Error画面 -->
  <div id="systemError">
    <div class="wrapper__outer">
      <div class="wrapper__inner text-center">
        <p>システムエラーが発生しました。管理者にご連絡下さい。</p>
        <b-img
          class="d-block m-auto"
          src="/img/yanwari19.png"
          fluid
          alt=""
        ></b-img>
        <b-link href="/">ホームに戻る</b-link>
      </div>
    </div>
  </div>
</template>
    
<script>
export default {
  name: "SystemError",
};
</script>
    
<style scoped>
.wrapper__outer {
  position: relative;
  height: 100vh;
  width: 100vw;
}

.wrapper__inner {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -webkit-transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
}

.wrapper__inner {
  font-size: 2.5rem;
  color: #bf4d4d;
}

.wrapper img {
  height: 60%;
  width: 60%;
}

@media (max-width: 575.98px) {
  .wrapper__inner {
    font-size: 0.9rem;
  }
}

a:hover {
  text-decoration: none;
}
</style>
