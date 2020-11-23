<template>
  <div class="header">
    <b-jumbotron class="shadow-sm" tag="header" header-level="5" :style="[img]">
      <!-- slotはカスタムタグ直下の必要がありdivタグで囲めない、そのためh1,p要素をベタ書き -->
      <!-- <template #header>{{ title }}</template>
      <template #lead>{{ lead }}</template> -->
      <div class="header-bgcolor text-center">
        <h1 class="display-3">Gourmet</h1>
        <p class="lead">グルメ情報検索サイト</p>
      </div>

      <div class="mt-4 px-4 text-right">
        <Sidebar></Sidebar>
      </div>
    </b-jumbotron>
  </div>
</template>

<script>
// ビルド時にモジュールとして読み込まれるためインポート
import BGImage from '@/assets/img/pizza.jpg';
import Sidebar from '@/components/Sidebar.vue';

export default {
  name: 'Header',
  components: {
    Sidebar
  },
  data() {
    return {
      title: 'Gourmet!', // タイトル
      lead: 'グルメ情報検索サイト', // サブタイトル
      // 背景画像
      // パス書き換えはimport文より
      img: {
        background: 'url(' + BGImage + ')'
      }
    };
  }
};
</script>

<style scoped>
/* ヘッダー全体背景とフォント */
.header header {
  background-position: center !important;
  background-size: cover !important;
  font-family: 'Amatic SC', cursive;
  color: #4d320b;
  text-align: center !important;
}

/* タイトルとリード文/背景色 */
.header .header-bgcolor {
  background-color: rgba(255, 255, 255, 0.7);
  display: inline-block;
  padding: 1.8rem;
  border-radius: 50%;
}
</style>
