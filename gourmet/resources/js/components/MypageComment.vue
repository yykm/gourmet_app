<template>
  <!-- 口コミ履歴 -->
  <div id="myPageComment">
    <div class="wrapper p-3">
      <!-- タイトル -->
      <h2 class="title mb-3">投稿した口コミ</h2>

      <div v-if="comments.length" class="comments px-2">
        <!-- 投稿した口コミ一覧 -->
        <b-card
          v-for="(comment, i) in comments"
          :key="i"
          :img-src="url(comment)"
          img-height="200"
          img-width="200"
          img-alt="Card image"
          img-left
          class="border-0 py-4"
          body-class="text-border py-2"
        >
          <b-card-sub-title class="mb-md-2 my-2">{{
            comment.shop.name
          }}</b-card-sub-title>
          <b-card-text>
            <h6 class="mt-0 mb-3">{{ comment.created_at }}&nbsp;投稿</h6>
            <p class="mb-0">
              <span class="ml-3">{{ comment.content }} </span>
            </p>
          </b-card-text>
        </b-card>
      </div>

      <!-- 投稿した口コミが１つも無い場合に表示 -->
      <div v-else class="nothing">
        <p>投稿したレビューはありません</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "MypageComment",
  props: {
    // 口コミ情報
    comments: {
      type: Array,
      required: false,
    },
  },
  methods: {
    // 口コミと一緒に投稿した写真のURLを返却
    url(comment) {
      return comment.photo ? comment.photo.url : "/img/l_e_others_500.png";
    },
  },
};
</script>

<style scoped>
.title {
  font-size: 1.5rem;
}

.comments__text {
  width: 90%;
  height: 300px;
  background-color: aliceblue;
}

.card {
  border-radius: 0rem;
  border-top-color: rgba(0, 0, 0, 0.125) !important;
  border-top-style: solid !important;
  border-top-width: 1px !important;
}

.card:first-child {
  border-top: none !important;
  padding-top: 0 !important;
}

.card:last-child {
  padding-bottom: 0.5em !important;
}

.card-img-left {
  padding: 0.25rem;
  border: 1px solid #dee2e6 !important;
}

@media (max-width: 767.98px) {
  .card {
    flex-direction: column !important;
    align-items: center;
  }

  .card-text {
    margin-top: 2rem;
    text-align: center;
  }
}
</style>
