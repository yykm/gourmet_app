<template>
  <div class="photo">
      <b-img class="photo-img" alt="aiu" v-b-modal="photoId">Show Modal</b-img>
    <b-modal
      :id="photoId"
      :centered="true"
      :ok-only="true"
      ok-title="閉じる"
      ok-variant="secondary"
      content-class="text-center"
      footer-class="justify-content-center"
      :hide-header="true"
    >
      {{ photoId }}の写真ページ
    </b-modal>
  </div>
</template>

<script>
export default {
  name: "Photo",
  props: {
    // 店舗ID
    photoId: {
      type: String,
      required: true
    }
  }
};
</script>

<style scoped>
.photo {
  height: 300px;
  width: 100%;
  background-color:aliceblue;
}

.photo-img {
  min-width: 100%;
  min-height:100%
}
</style>
