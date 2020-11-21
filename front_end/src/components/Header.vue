<template>
  <div class="header">
    <b-jumbotron class="shadow-sm" tag="header" :style="[img]">
      <template #header>{{ title }}</template>
      <template #lead>{{ lead }}</template>
      <div class="text-right">
        <b-button variant="light">ログイン</b-button>
        <b-button variant="light">新規登録</b-button>
      </div>
    </b-jumbotron>
  </div>
</template>

<script>
// ビルド時にモジュールとして読み込まれるためインポート
import BGImage from '@/assets/img/pizza.jpg';

export default {
  name: 'Header',
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
/* ヘッダー背景 */
.header header {
  background-position: center !important;
  background-size: cover !important;
}

/* タイトルとリード文の文字色 */
.header h1,
p {
  color: #424242;
}
</style>
