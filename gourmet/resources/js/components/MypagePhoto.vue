<template>
  <div id="mypagePhoto">
    <h2 class="p-3 title">過去の投稿写真</h2>
    <div class="wrapper pb-2">
    <carousel :per-page="3" :navigation-enabled="true">
      <slide v-for="(photo, i) in photos" :key="i">
        <div class="items px-2">
          <b-card
            :title="'店舗ID:' + photo.shop.id"
            :img-src="photo.url"
            img-alt="Image"
            img-top
            img-height="300"
            tag="article"
            body-class="text-center"
          >
            <b-card-text>
                <p>投稿日：{{ photo.created_at }}</p> 
            </b-card-text>
          </b-card>
        </div>
      </slide>
    </carousel>
    </div>
  </div>
</template>

<script>
import { Carousel, Slide } from "vue-carousel";

export default {
  name: "MypagePhoto",
  components: {
    Carousel,
    Slide,
  },
  props: {
    photos: {
      type: Array,
      required: false,
    },
  },
};
</script>

<style scoped>
.title{
  font-size: 1.5rem;
}
</style>
