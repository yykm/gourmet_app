<template>
  <div class="photo">
    <figure class="photo__wrapper" v-b-modal="photo.id">
      <b-img :src="photo.url" class="photo__img">Show Modal</b-img>
    </figure>

    <!-- モーダル表示部 -->
    <b-modal
      size="xl"
      :hide-header-close="true"
      :id="photo.id"
      :centered="true"
      :ok-only="true"
      ok-title="閉じる"
      ok-variant="secondary"
      content-class="text-center"
      footer-class="justify-content-center"
      :title="photo.user.name + 'さんの投稿'"
      title-class="mx-auto"
    >
      <b-img :src="photo.url">Show Modal</b-img>
    </b-modal>
  </div>
</template>

<script>
export default {
  name: "Photo",
  props: {
    // 写真オブジェクト
    photo: {
      type: Object,
      required: true
    }
  }
};
</script>

<style scoped>
.photo,
.photo__wrapper,
.photo__img {
  height: 100%;
  width: 100%;
}

.modal-body img {
  max-width: 90%;
  max-height: 90%;
}
</style>
