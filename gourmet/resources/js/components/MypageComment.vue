<template>
  <div id="mypagePhoto">
    <h2 class="p-3 title">過去のコメント投稿</h2>
    <div class="wrapper pb-2">
      <carousel :per-page="1" :navigation-enabled="true">
        <slide v-for="(comment, i) in comments" :key="i">
          <div class="items px-2">
            <b-card no-body class="overflow-hidden mx-auto">
              <b-row no-gutters>
                <b-col md="6">
                  <b-card-img
                    :src="
                      comment.photo !== null
                        ? comment.photo.url
                        : '/img/l_e_others_500.png'
                    "
                    alt="Image"
                    class="rounded-0 border"
                  ></b-card-img>
                </b-col>
                <b-col md="6">
                  <b-card-body :title="'投稿日：' + comment.created_at">
                    <b-card-text class="ml-3">
                      {{ comment.content }}
                    </b-card-text>
                  </b-card-body>
                </b-col>
              </b-row>
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
  methods: {
    url(comment) {
      // 選択された画像、さもなければ初期画像
      return comment.photo.url ?? "/img/l_e_others_500.png";
    },
  },
  props: {
    comments: {
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

.card-img {
  max-height: 100%;
  max-width: 100%;
}

.card-title {
  font-size: 1.2rem;
}

.card {
  width: 85% !important;
}
</style>
