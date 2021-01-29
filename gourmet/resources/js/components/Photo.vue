<template>
  <div class="photo">
    <figure class="photo__wrapper" v-b-modal="photo.id">
      <b-img
        :src="photo.url"
        :alt="'Photo by ${item.user.name}'"
        class="photo__img"
      ></b-img>
    </figure>
    <div class="photo__controls mt-2 mr-2 d-flex align-items-stretch">
      <!-- 画像ダウンロードボタン -->
      <a
        class="photo__action px-2"
        title="Download photo"
        @click.stop
        :href="`/photos/${photo.id}/download`"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="18"
          height="18"
          fill="currentColor"
          class="bi bi-box-arrow-down"
          viewBox="0 0 16 16"
        >
          <path
            fill-rule="evenodd"
            d="M3.5 10a.5.5 0 0 1-.5-.5v-8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 0 0 1h2A1.5 1.5 0 0 0 14 9.5v-8A1.5 1.5 0 0 0 12.5 0h-9A1.5 1.5 0 0 0 2 1.5v8A1.5 1.5 0 0 0 3.5 11h2a.5.5 0 0 0 0-1h-2z"
          />
          <path
            fill-rule="evenodd"
            d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z"
          />
        </svg>
      </a>
    </div>
    <div class="photo__username mb-2 ml-2">{{ photo.user.name }}</div>

    <!-- モーダル表示部 -->
    <b-modal
      size="xl"
      :id="photo.id"
      :hide-header="true"
      :centered="true"
      :ok-only="true"
      ok-title="閉じる"
      ok-variant="secondary"
      content-class="text-center"
      footer-class="justify-content-center border-0"
    >
      <div
        class="photo__header d-flex justify-content-between align-items-center mb-3 px-2"
      >
        <span>{{ "Posted by " + photo.user.name }}</span>

        <!-- 画像ダウンロードボタン -->
        <a
          class="photo__action download__detail border px-2"
          title="Download photo"
          @click.stop
          :href="`/photos/${photo.id}/download`"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="19"
            height="19"
            fill="currentColor"
            class="bi bi-box-arrow-down mr-1"
            viewBox="0 0 16 16"
          >
            <path
              fill-rule="evenodd"
              d="M3.5 10a.5.5 0 0 1-.5-.5v-8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 0 0 1h2A1.5 1.5 0 0 0 14 9.5v-8A1.5 1.5 0 0 0 12.5 0h-9A1.5 1.5 0 0 0 2 1.5v8A1.5 1.5 0 0 0 3.5 11h2a.5.5 0 0 0 0-1h-2z"
            />
            <path
              fill-rule="evenodd"
              d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z"
            /></svg
          >Download
        </a>
      </div>
      <figure class="modal__wrapper">
        <b-img
          :src="photo.url"
          :alt="'Photo by ${item.user.name}'"
          class="modal__img"
        />
      </figure>
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

.photo {
  position: relative;
}

.photo__controls,
.photo__username {
  position: absolute;
}

.photo__controls {
  top: 0;
  right: 0;
}

.photo__username {
  bottom: 0;
  left: 0;
  color: white;
  font-size: 1.2rem;
}

.modal__img {
  max-width: 90%;
  max-height: 90%;
}

.photo__wrapper {
  outline: none;
  background: #000;
}

.photo__img:hover {
  opacity: 0.7;
  transition-duration: 0.6s;
}

.photo__action {
  color: #212529;
  border-radius: 0.2rem;
  background-color: #f8f9fa;
  border-color: #f8f9fa;
  padding: 0.25rem 0.5rem;
  text-decoration: none;
}

.download__detail {
  border-color: #ccd3da70;
}
</style>
