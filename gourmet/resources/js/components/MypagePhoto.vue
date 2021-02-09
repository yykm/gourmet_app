<template>
  <div id="mypagePhoto">
    <h2 class="title mb-3 px-3 pt-3">投稿した写真</h2>
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
        :caption="photo.shop.name"
        :text="photo.created_at + ' 投稿'"
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
