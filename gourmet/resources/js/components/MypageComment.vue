<template>
  <div id="myPageComment">
    <h2 data-v-7f3aefc8="" class="p-3 title">投稿したレビュー</h2>
    <div class="wrapper px-2">
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
        <b-card-text>
          <h6 class="mt-0 mb-3">
            {{ "投稿日：" + comment.created_at }}
          </h6>
          <p class="mb-0">
            <span class="ml-3">{{ comment.content }} </span>
          </p>
        </b-card-text>
      </b-card>
    </div>
  </div>
</template>

<script>
export default {
  name: "MypageComment",
  props: {
    comments: {
      type: Array,
      required: false,
    },
  },
  methods: {
    url(comment) {
      return comment.photo ? comment.photo.url : "/img/l_e_others_500.png";
    },
  },
};
</script>

<style scoped>
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
