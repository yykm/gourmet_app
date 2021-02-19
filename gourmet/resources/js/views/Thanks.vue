<template>
  <!-- 退会画面 -->
  <div id="thanks">
    <div class="wrapper__outer">
      <div class="wrapper__inner text-center">
        <p>ご利用ありがとうございました！</p>
        <b-img
          class="d-block m-auto"
          src="/img/yanwari14.png"
          fluid
          alt="Responsive image"
        ></b-img>
        <b-link href="/">ホームに戻る</b-link>
      </div>
    </div>
  </div>
</template>
    
<script>
export default {
  name: "Thanks",
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
