<template>
  <div class="reserved">
    <Header />

    <b-container class="shadow-sm mb-3 mt-4">
      <div class="link__area py-3">
        <b-link to="/"
          ><svg
            xmlns="http://www.w3.org/2000/svg"
            width="18"
            height="18"
            fill="currentColor"
            class="bi bi-chevron-left mr-1"
            viewBox="0 0 21 21"
          >
            <path
              fill-rule="evenodd"
              d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"
            /></svg
          >トップに戻る</b-link
        >
      </div>
      <div class="result__area">
        <b-card body-class="text-center bg-color">
          <b-card-title class="mb-3"
            >予約が完了しました<br />当日はそのままお店へご来店ください。</b-card-title
          >
          <b-card-sub-title class="mb-5"
            >ご記入のメールアドレス「{{
              reservation.email
            }}」宛に、予約内容の控えを送信しました。</b-card-sub-title
          >
          <b-card-text>
            <b-button variant="secondary" class="px-4">ご予約の確認</b-button>
          </b-card-text>
        </b-card>
      </div>
    </b-container>
  </div>
</template>

<script>
import Header from "./../components/Header.vue";

export default {
  name: "Reserved",
  components: {
    Header,
  },
  props: {
    reservation: {
      type: Object,
      required: true,
    },
  },
};
</script>
<style scoped>
.container {
  background-color: #fff;
  padding: 2rem !important;
}

.bg-color {
  background-color: #85a0a969;
}

.card-title {
  line-height: 1.5em;
}

.link__area a{
  color: #0000009e;
  font-size: 1.2rem;
  text-decoration: none;
}
</style>
