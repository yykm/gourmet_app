<template>
  <div id="mypageReservaiton">
    <div class="wrapper p-3">
      <h2 class="mb-3 title">お気に入りのお店</h2>

      <b-table
        v-if="reservations.length"
        :items="items"
        :fields="fields"
        striped
        responsive="sm"
      >
        <!-- 概要表示部 -->
        <template #cell(show_details)="row">
          <b-button size="sm" @click="row.toggleDetails" class="mr-2">
            詳細{{ row.detailsShowing ? "を閉じる" : "を表示" }}
          </b-button>
        </template>

        <!-- 詳細表示-->
        <template #row-details="row">
          <b-card>
            <b-row
              v-for="(item, item_num) in row.item.detail"
              :key="item_num"
              class="mb-2"
            >
              <b-col sm="3" class="text-sm-right"
                ><b>{{ item.key }}</b></b-col
              >
              <b-col>{{ item.value }}</b-col>
            </b-row>
          </b-card>
        </template>
      </b-table>
      <div v-else class="nothing">
        <p>予約履歴はありません</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "MypageReservation",
  data() {
    return {
      // テーブルヘッダー
      fields: [
        { key: "date", label: "予約日" },
        { key: "shop", label: "予約した日時" },
        { key: "show_details", label: "詳細を見る" },
      ],
    };
  },
  props: {
    reservations: {
      type: Array,
      required: false,
    },
  },
  computed: {
    // セルデータ
    items() {
      let items = [];

      this.reservations.forEach((reservation) => {
        items.push({
          date: reservation.created_at, // 予約日
          shop: reservation.shop.name, //予約した店舗
          /**  詳細表示部 */
          detail: {
            name: { key: "お名前", value: reservation.name }, // 予約名義
            number: { key: "人数", value: reservation.number }, // 人数
            date: { key: "来店日", value: reservation.date }, // 予約時刻
            time: { key: "来店時刻", value: reservation.time }, // 予約時刻
            phone_num: { key: "電話番号", value: reservation.phone_num }, // 電話番号
            email: { key: "Eメール", value: reservation.email }, // Eメールアドレス
            purpose: { key: "利用目的", value: reservation.purpose }, // 利用目的
            request: { key: "ご要望等", value: reservation.request }, // ご要望・ご意見
          },
        });
      });

      return items;
    },
  },
};
</script>

<style scoped>
.title {
  font-size: 1.5rem;
}
</style>
