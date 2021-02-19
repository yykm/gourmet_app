<template>
  <!-- 写真の投稿履歴 -->
  <div id="mypagePhoto">
    <!-- タイトル -->
    <h2 class="title mb-3 px-3 pt-3">投稿した写真</h2>

    <!-- 投稿した写真の履歴一覧のカルーセル表示 -->
    <b-carousel
      v-if="photos.length"
      :interval="0"
      controls
      indicators
      background="#ababab"
      style="text-shadow: 1px 1px 2px #333"
    >
      <b-carousel-slide
        v-for="(photo, i) in photos"
        :key="i"
        :caption="photo.shop.name"
        :text="photo.created_at + ' 投稿'"
        :img-src="photo.url"
      ></b-carousel-slide>
    </b-carousel>

    <!-- 投稿した写真が１枚もない場合 -->
    <div v-else class="nothing px-3 pb-3">
      <p>投稿した写真はありません</p>
    </div>
  </div>
</template>

<script>
export default {
  name: "MypagePhoto",
  props: {
    // 写真情報
    photos: {
      type: Array,
      required: false,
    },
  },
};
</script>

<style scoped>
.title {
  font-size: 1.5rem;
}
</style>
<style>
#mypagePhoto .carousel-inner {
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
}
#mypagePhoto h3 {
  font-size: 1.8rem;
}

@media (max-width: 575.98px) {
  #mypagePhoto h3,
  p {
    font-size: 0.9rem;
    margin-bottom: 0 !important;
  }
}

@media (min-width: 576px) and (max-width: 991.98px) {
  #mypagePhoto h3 {
    font-size: 1.3rem;
    margin-bottom: 0 !important;
  }
}
</style>
