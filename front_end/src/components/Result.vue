<!-- Input:shops/shopsCount(getter) -->
<template>
  <div class="result" v-if="shops.length > 0">
    <p class="">{{ shopsCount }}件表示中</p>

    <ul class="p-list">
      <li v-for="shop of shops" v-bind:key="shop.id">
        <!-- お店のヘッダー部 -->
        <div class="p-name-wrap">
          <!-- // ロゴ画像 -->
          <p class="p-shop-logo"><img :src="shop.logo" alt="" /></p>
          <!-- お店の種類・副題・店名 -->
          <div class="p-name-content">
            <p class="p-category">{{ shop.category }}</p>
            <p class="p-catch">{{ shop.catch }}</p>
            <p class="p-name">{{ shop.name }}</p>
          </div>
        </div>

        <!-- お店のメインコンテンツ -->
        <div calss="p-content">
          <!-- お店の画像  -->
          <div class="p-main-img">
            <p>
              <a :href="shop.url" target="_blank" rel="noopener noreferrer">
                <img :src="shop.photo" :alt="shop.name" /><br />
                <span class="mt20 spmt20 m-blank">店舗ページを見る</span>
              </a>
            </p>
          </div>

          <!-- お店の詳細情報 -->
          <table class="p-table">
            <tr>
              <th>住所</th>
              <td>
                {{ shop.address }}<br />
                <a
                  :href="shop.map"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="m-txt-hover p-map-link m-blank"
                  >Google Mapで見る</a
                >
              </td>
            </tr>
            <tr>
              <th>アクセス</th>
              <td>{{ shop.access }}</td>
            </tr>
            <tr>
              <th>営業時間</th>
              <td>{{ shop.open }}</td>
            </tr>
            <tr>
              <th>ランチ</th>
              <td>{{ shop.lunch }}</td>
            </tr>
            <tr>
              <th>クレジット<br class="nopc" />カード</th>
              <td>{{ shop.card }}</td>
            </tr>
            <tr>
              <th>禁煙・喫煙</th>
              <td>{{ shop.smoking }}</td>
            </tr>
            <tr>
              <th>Wi-Fi</th>
              <td>{{ shop.wifi }}</td>
            </tr>
            <tr>
              <th>駐車場</th>
              <td>{{ shop.parking }}</td>
            </tr>
            <tr>
              <th>平均予算</th>
              <td>{{ shop.average }}</td>
            </tr>
            <tr v-if="shop.memo">
              <th>料金備考</th>
              <td>{{ shop.memo }}</td>
            </tr>
            <tr>
              <th>クーポン</th>
              <td>
                <a
                  :href="shop.coupon"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="m-blank m-txt-hover"
                  >クーポンを見る</a
                >
              </td>
            </tr>
          </table>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'Result'
};
</script>

<style scoped></style>
