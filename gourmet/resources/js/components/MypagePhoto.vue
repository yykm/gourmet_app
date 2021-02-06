<template>
  <div id="mypagePhoto">
    <h2 class="p-3 title">過去の投稿写真</h2>
    <b-carousel
      :interval="0"
      controls
      indicators
      background="#ababab"
      style="text-shadow: 1px 1px 2px #333"
    >
      <!-- Text slides with image -->
      <b-carousel-slide
        v-for="(photo, i) in photos"
        :key="i"
        :caption="'店舗ID：' + photo.shop.id"
        :text="'投稿日：' + photo.created_at"
        :img-src="photo.url"
      ></b-carousel-slide>
    </b-carousel>
  </div>
</template>

<script>
export default {
  name: "MypagePhoto",
  props: {
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
  max-width: 500px;
  margin-left: auto;
  margin-right: auto;
}
</style>
