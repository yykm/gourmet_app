<template>
  <main role="main">
    <!-- 検索結果の表示領域 -->
    <shopInfo :shops="getShops" :shopsCount="shopsCount"></shopInfo>

    <h2 class="text-center m-5 border-bottom p-1">Works</h2>

    <div class="container">
      <section class="row d-flex mb-4">
        <a
          href="#"
          class="col-md-3 col-sm-8 p-0 m-auto text-decoration-none btn-outline-dark"
        >
          <img
            src="https://myajo.net/wp/wp-content/uploads/2019/05/btn-outline.jpg"
            alt=""
            class="w-100"
          />
          <h3 class="text-center text-black">たいとる</h3>
          <p class="text-center text-black">
            サンプルテキストサンプルテキストサンプルテキスト
          </p>
        </a>
        <a
          href="#"
          class="col-md-3 col-sm-8 p-0 m-auto text-decoration-none btn-outline-dark"
        >
          <img src="images/image2.jpg" alt="" class="w-100" />
          <h3 class="text-center">たいとる</h3>
          <p class="text-center">
            サンプルテキストサンプルテキストサンプルテキスト
          </p>
        </a>
        <a
          href="#"
          class="col-md-3 col-sm-8 p-0 m-auto text-decoration-none btn-outline-dark"
        >
          <img src="images/image3.jpg" alt="" class="w-100" />
          <h3 class="text-center">たいとる</h3>
          <p class="text-center">
            サンプルテキストサンプルテキストサンプルテキスト
          </p>
        </a>
      </section>

      <section class="row d-flex">
        <a
          href="#"
          class="col-md-3 col-sm-8 p-0 m-auto text-decoration-none btn-outline-dark"
        >
          <img src="images/image3.jpg" alt="" class="w-100" />
          <h3 class="text-center">たいとる</h3>
          <p class="text-center">
            サンプルテキストサンプルテキストサンプルテキスト
          </p>
        </a>
        <a
          href="#"
          class="col-md-3 col-sm-8 p-0 m-auto text-decoration-none btn-outline-dark"
        >
          <img src="images/image.jpg" alt="" class="w-100" />
          <h3 class="text-center">たいとる</h3>
          <p class="text-center">
            サンプルテキストサンプルテキストサンプルテキスト
          </p>
        </a>
        <a
          href="#"
          class="col-md-3 col-sm-8 p-0 m-auto text-decoration-none btn-outline-dark"
        >
          <img src="images/image2.jpg" alt="" class="w-100" />
          <h3 class="text-center">たいとる</h3>
          <p class="text-center">
            サンプルテキストサンプルテキストサンプルテキスト
          </p>
        </a>
      </section>
    </div>

    <!-- ************ -->

    <h2 class="text-center m-5 border-bottom p-1">Shop</h2>

    <section class="container mb-5">
      <div class="row p-0 d-flex mb-2 justify-content-center">
        <div class="col-md-10 col-lg-6">
          <img
            src="https://myajo.net/wp/wp-content/uploads/2019/05/btn-outline.jpg"
            alt="#"
            class="w-100"
          />
        </div>
        <div class="col-md-10 col-lg-6 pt-4">
          <h3 class="text-center">たいとる</h3>
          <p class="text-center">
            サンプルテキストサンプルテキストサンプルテキスト
          </p>
        </div>
      </div>
      <div class="row p-0 d-flex mb-2 flex-row-reverse justify-content-center">
        <div class="col-md-10 col-lg-6">
          <img src="images/image2.jpg" alt="#" class="w-100" />
        </div>
        <div class="col-md-10 col-lg-6 pt-4">
          <h3 class="text-center">たいとる</h3>
          <p class="text-center">
            サンプルテキストサンプルテキストサンプルテキスト
          </p>
        </div>
      </div>
    </section>
  </main>
</template>

<script>
import shopInfo from '@/components/shopInfo.vue';
import { mapGetters } from 'vuex';

export default {
  name: 'Main',
  components: {
    shopInfo
  },
  computed: {
    ...mapGetters(['getShops', 'shopsCount'])
  }
};
</script>

<style scoped></style>
