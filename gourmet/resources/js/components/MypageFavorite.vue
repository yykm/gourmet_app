<template>
  <div id="myPageFavorite">
    <h2 class="p-3 title">過去の投稿写真</h2>
    <div class="wrapper pb-2">
      <carousel :per-page="3" :navigation-enabled="true">
        <slide v-for="(favorite, i) in favorites" :key="i">
          <div class="items px-2">
            <b-card
              :title="'店舗ID：'　+ favorite.shop_id"
              img-src="https://picsum.photos/600/300/?image=25"
              img-alt="Image"
              img-top
              tag="article"
              style="max-width: 20rem"
              class="mb-2"
            >
              <b-card-text>
                お気に入りにした日付：{{ favorite.created_at }}
              </b-card-text>

              <b-button href="#" variant="primary">Go somewhere</b-button>
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
  name: "MypageFavorite",
  components: {
    Carousel,
    Slide,
  },
  props: {
    favorites: {
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
