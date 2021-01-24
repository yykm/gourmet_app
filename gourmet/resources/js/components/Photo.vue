<template>
  <div id="photo">

    <b-button v-b-modal.modal-1>写真ボタン</b-button>

    <b-modal
      id="modal-1"
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
</style>
