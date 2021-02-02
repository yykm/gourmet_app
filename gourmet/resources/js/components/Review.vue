<template>
  <div id="review">
    <b-card
      :img-src="url"
      img-height="200"
      img-width="200"
      img-alt="Card image"
      img-left
      class="py-4 border-0"
      body-class="text-border py-2"
    >
      <b-card-text>
        <h6 class="mt-0 mb-3">
          {{ review.id + "." }}
          &thinsp;
          {{ "投稿者：" + review.user.name }}
          &thinsp;
          {{ review.created_at }}
        </h6>
        <p class="mb-0">
          <span class="ml-3">{{ review.content }} </span>
        </p>
      </b-card-text>
    </b-card>
  </div>
</template>

<script>
export default {
  name: "Review",
  props: {
    review: {
      type: Object,
      required: false
    }
  },
  computed: {
    url() {
      return this.review.photo !== null
        ? this.review.photo.url
        : "/img/l_e_others_500.png";
    }
  }
};
</script>

<style scoped>
.review__text {
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
